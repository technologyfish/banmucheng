<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Public: return tree structure
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->orderBy('sort_order')
            ->with('children')
            ->get()
            ->map(function ($cat) {
                return [
                    'id'       => $cat->id,
                    'name_zh'  => $cat->name_zh,
                    'name_en'  => $cat->name_en,
                    'children' => $cat->children->map(function ($child) {
                        return [
                            'id'      => $child->id,
                            'name_zh' => $child->name_zh,
                            'name_en' => $child->name_en,
                        ];
                    }),
                ];
            });

        return response()->json($categories);
    }

    // Admin: flat list
    public function adminIndex()
    {
        $categories = Category::orderBy('sort_order')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name_zh'   => 'required|string|max:100',
            'name_en'   => 'required|string|max:100',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'sort_order' => 'nullable|integer',
        ]);

        $category = Category::create([
            'name_zh'    => $request->name_zh,
            'name_en'    => $request->name_en,
            'parent_id'  => $request->parent_id,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $this->validate($request, [
            'name_zh'    => 'sometimes|string|max:100',
            'name_en'    => 'sometimes|string|max:100',
            'parent_id'  => 'nullable|integer',
            'sort_order' => 'nullable|integer',
        ]);

        $category->update($request->only(['name_zh', 'name_en', 'parent_id', 'sort_order']));

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return response()->json(['message' => '该分类下还有产品，无法删除'], 422);
        }

        $category->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * 调整产品分类排序（上移/下移）
     */
    public function reorder(Request $request, $id)
    {
        $this->validate($request, [
            'direction' => 'required|in:up,down',
        ]);

        $cat       = Category::findOrFail($id);
        $direction = $request->input('direction');

        // 归一化所有分类的 sort_order
        $all = Category::orderBy('sort_order')->orderBy('id')->get();
        foreach ($all as $i => $c) {
            if ($c->sort_order !== $i) {
                $c->sort_order = $i;
                $c->save();
            }
        }
        $cat->refresh();

        if ($direction === 'up') {
            $sibling = Category::where('sort_order', '<', $cat->sort_order)
                ->orderByDesc('sort_order')->first();
        } else {
            $sibling = Category::where('sort_order', '>', $cat->sort_order)
                ->orderBy('sort_order')->first();
        }

        if (!$sibling) {
            return response()->json(['message' => '已到边界'], 400);
        }

        $tmp             = $cat->sort_order;
        $cat->sort_order = $sibling->sort_order;
        $sibling->sort_order = $tmp;
        $cat->save();
        $sibling->save();

        return response()->json(['message' => 'ok']);
    }
}
