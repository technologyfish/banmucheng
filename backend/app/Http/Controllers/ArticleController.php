<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // ────────────────────────────────────────────
    //  Article Categories
    // ────────────────────────────────────────────

    /**
     * 获取指定 parent_id 下的分类（不传则查根分类）
     */
    public function categoryIndex(Request $request)
    {
        $parentId = $request->has('parent_id') ? $request->input('parent_id') : null;

        if ($request->has('parent_id')) {
            // 查子分类
            $cats = ArticleCategory::where('parent_id', $parentId)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        } else {
            // 查根分类（不带 parent_id 参数时返回全部，兼容旧接口）
            $cats = ArticleCategory::orderBy('sort_order')->orderBy('id')->get();
        }

        return response()->json($cats);
    }

    /**
     * 获取单个分类信息（含面包屑路径）
     */
    public function categoryShow($id)
    {
        $cat = ArticleCategory::findOrFail($id);

        // 构建面包屑
        $breadcrumbs = [];
        $current = $cat;
        while ($current) {
            array_unshift($breadcrumbs, ['id' => $current->id, 'name' => $current->name]);
            $current = $current->parent_id ? ArticleCategory::find($current->parent_id) : null;
        }

        return response()->json(array_merge($cat->toArray(), ['breadcrumbs' => $breadcrumbs]));
    }

    public function categoryStore(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required|string|max:100',
            'sort_order' => 'integer',
        ]);

        $slug = Str::slug($request->input('name'), '-') ?: 'cat-' . time();
        // Avoid slug collision
        if (ArticleCategory::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $cat = ArticleCategory::create([
            'name'       => $request->input('name'),
            'slug'       => $slug,
            'parent_id'  => $request->input('parent_id', null),
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => true,
        ]);

        return response()->json($cat, 201);
    }

    public function categoryUpdate(Request $request, $id)
    {
        $cat = ArticleCategory::findOrFail($id);

        $this->validate($request, [
            'name'       => 'required|string|max:100',
            'sort_order' => 'integer',
        ]);

        $cat->update([
            'name'       => $request->input('name'),
            'parent_id'  => $request->input('parent_id', $cat->parent_id),
            'sort_order' => $request->input('sort_order', $cat->sort_order),
            'is_active'  => $request->input('is_active', $cat->is_active),
        ]);

        return response()->json($cat);
    }

    public function categoryDestroy($id)
    {
        $cat = ArticleCategory::findOrFail($id);
        // 将子分类提升到当前分类的父级
        ArticleCategory::where('parent_id', $id)->update(['parent_id' => $cat->parent_id]);
        $cat->delete();
        return response()->json(['message' => 'deleted']);
    }

    /**
     * 调整分类排序（同父级内上移/下移）
     * 先归一化同级分类的 sort_order，再交换相邻两条。
     */
    public function categoryReorder(Request $request, $id)
    {
        $this->validate($request, [
            'direction' => 'required|in:up,down',
        ]);

        $cat       = ArticleCategory::findOrFail($id);
        $direction = $request->input('direction');

        // 1. 取同父级所有分类，按当前展示顺序
        $all = ArticleCategory::where('parent_id', $cat->parent_id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // 2. 归一化 sort_order
        foreach ($all as $i => $c) {
            if ($c->sort_order !== $i) {
                $c->sort_order = $i;
                $c->save();
            }
        }
        $cat->refresh();

        // 3. 找相邻分类
        if ($direction === 'up') {
            $sibling = ArticleCategory::where('parent_id', $cat->parent_id)
                ->where('sort_order', '<', $cat->sort_order)
                ->orderByDesc('sort_order')
                ->first();
        } else {
            $sibling = ArticleCategory::where('parent_id', $cat->parent_id)
                ->where('sort_order', '>', $cat->sort_order)
                ->orderBy('sort_order')
                ->first();
        }

        if (!$sibling) {
            return response()->json(['message' => '已到边界'], 400);
        }

        // 4. 交换 sort_order
        $tmpOrder        = $cat->sort_order;
        $cat->sort_order = $sibling->sort_order;
        $sibling->sort_order = $tmpOrder;
        $cat->save();
        $sibling->save();

        return response()->json(['message' => 'ok']);
    }

    // ────────────────────────────────────────────
    //  Articles (Admin)
    // ────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $q = Article::with('category')->orderBy('sort_order')->orderByDesc('published_at');

        if ($request->has('search') && $request->input('search') !== '') {
            $q->where('title', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->has('category_id') && $request->input('category_id') !== '') {
            $q->where('category_id', $request->input('category_id'));
        }

        $perPage = (int) $request->input('per_page', 20);
        return response()->json($q->paginate($perPage));
    }

    public function adminShow($id)
    {
        $article = Article::with('category')->findOrFail($id);
        return response()->json($article);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title'   => 'required|string|max:255',
        ]);

        $article = Article::create([
            'category_id'         => $request->input('category_id'),
            'title'               => $request->input('title'),
            'title_en'            => $request->input('title_en'),
            'description'         => $request->input('description'),
            'description_en'      => $request->input('description_en'),
            'cover_image'         => $request->input('cover_image'),
            'content'             => $request->input('content'),
            'content_en'          => $request->input('content_en'),
            'published_at'        => $request->input('published_at') ?: \Carbon\Carbon::now(),
            'is_active'           => $request->input('is_active', 1),
            'is_frontend_visible' => $request->input('is_frontend_visible', 1),
            'sort_order'          => $request->input('sort_order', 0),
        ]);

        return response()->json($article, 201);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $this->validate($request, [
            'title' => 'required|string|max:255',
        ]);

        $article->update([
            'category_id'         => $request->input('category_id', $article->category_id),
            'title'               => $request->input('title'),
            'title_en'            => $request->input('title_en', $article->title_en),
            'description'         => $request->input('description', $article->description),
            'description_en'      => $request->input('description_en', $article->description_en),
            'cover_image'         => $request->input('cover_image', $article->cover_image),
            'content'             => $request->input('content', $article->content),
            'content_en'          => $request->input('content_en', $article->content_en),
            'published_at'        => $request->input('published_at', $article->published_at),
            'is_active'           => $request->input('is_active', $article->is_active),
            'is_frontend_visible' => $request->input('is_frontend_visible', $article->is_frontend_visible),
            'sort_order'          => $request->input('sort_order', $article->sort_order),
        ]);

        return response()->json($article);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json(['message' => 'deleted']);
    }

    /**
     * 调整文章排序（同分类内上移/下移）
     * 先将同分类文章按当前展示顺序归一化 sort_order，再交换相邻两条。
     */
    public function reorder(Request $request, $id)
    {
        $this->validate($request, [
            'direction' => 'required|in:up,down',
        ]);

        $article   = Article::findOrFail($id);
        $direction = $request->input('direction');

        // 1. 按前台展示顺序取同分类所有文章
        $all = Article::where('category_id', $article->category_id)
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->get();

        // 2. 归一化 sort_order（0,1,2,...），消除重复值
        foreach ($all as $i => $a) {
            if ($a->sort_order !== $i) {
                $a->sort_order = $i;
                $a->save();
            }
        }
        $article->refresh();

        // 3. 找相邻文章
        if ($direction === 'up') {
            $sibling = Article::where('category_id', $article->category_id)
                ->where('sort_order', '<', $article->sort_order)
                ->orderByDesc('sort_order')
                ->first();
        } else {
            $sibling = Article::where('category_id', $article->category_id)
                ->where('sort_order', '>', $article->sort_order)
                ->orderBy('sort_order')
                ->first();
        }

        if (!$sibling) {
            return response()->json(['message' => '已到边界'], 400);
        }

        // 4. 交换 sort_order
        $tmpOrder            = $article->sort_order;
        $article->sort_order = $sibling->sort_order;
        $sibling->sort_order = $tmpOrder;
        $article->save();
        $sibling->save();

        return response()->json(['message' => 'ok']);
    }

    // ────────────────────────────────────────────
    //  Public endpoints
    // ────────────────────────────────────────────

    public function publicIndex(Request $request)
    {
        $q = Article::with('category')
            ->where('is_active', true)
            ->where('is_frontend_visible', true)
            ->orderBy('sort_order')
            ->orderByDesc('published_at');

        if ($request->has('category_id')) {
            $q->where('category_id', $request->input('category_id'));
        }

        $perPage = (int) $request->input('per_page', 100);
        return response()->json($q->paginate($perPage));
    }

    public function publicShow($id)
    {
        $article = Article::with('category')
            ->where('is_active', true)
            ->findOrFail($id);
        return response()->json($article);
    }
}
