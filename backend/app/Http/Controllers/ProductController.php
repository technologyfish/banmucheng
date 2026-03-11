<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Public product list with filters
    public function index(Request $request)
    {
        $query = Product::active()->with(['category', 'images']);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_zh', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        if ($request->substrate) {
            $query->whereJsonContains('substrates', $request->substrate);
        }

        if ($request->thickness) {
            $query->whereJsonContains('thicknesses', $request->thickness);
        }

        $perPage = $request->per_page ?? 12;
        $products = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'data'  => $products->items(),
            'total' => $products->total(),
            'page'  => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
    }

    // Public product detail
    public function show($id)
    {
        $product = Product::active()->with(['category', 'images'])->findOrFail($id);
        return response()->json($product);
    }

    // Admin product list
    public function adminIndex(Request $request)
    {
        $query = Product::with(['category', 'images']);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_zh', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        $perPage = $request->per_page ?? 20;
        $products = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate($perPage);

        return response()->json([
            'data'      => $products->items(),
            'total'     => $products->total(),
            'page'      => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name_zh'      => 'required|string|max:200',
            'name_en'      => 'required|string|max:200',
            'category_id'  => 'nullable|integer|exists:categories,id',
            'description_zh' => 'nullable|string',
            'description_en' => 'nullable|string',
            'substrates'   => 'nullable|array',
            'specs'        => 'nullable|array',
            'thicknesses'  => 'nullable|array',
            'cover_image'  => 'nullable|string',
            'status'       => 'nullable|integer|in:0,1',
            'sort_order'   => 'nullable|integer',
            'image_ids'    => 'nullable|array',
        ]);

        $product = Product::create([
            'name_zh'        => $request->name_zh,
            'name_en'        => $request->name_en,
            'category_id'    => $request->category_id,
            'description_zh' => $request->description_zh,
            'description_en' => $request->description_en,
            'substrates'     => $request->substrates ?? [],
            'specs'          => $request->specs ?? [],
            'thicknesses'    => $request->thicknesses ?? [],
            'cover_image'    => $request->cover_image,
            'status'         => $request->status ?? 1,
            'sort_order'     => $request->sort_order ?? 0,
        ]);

        // Attach uploaded images
        if ($request->image_ids) {
            foreach ($request->image_ids as $index => $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'sort_order' => $index,
                ]);
            }
            // Set cover image to first image if not set
            if (!$product->cover_image && count($request->image_ids) > 0) {
                $product->update(['cover_image' => $request->image_ids[0]]);
            }
        }

        return response()->json($product->load(['category', 'images']), 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->validate($request, [
            'name_zh'        => 'sometimes|string|max:200',
            'name_en'        => 'sometimes|string|max:200',
            'category_id'    => 'nullable|integer',
            'description_zh' => 'nullable|string',
            'description_en' => 'nullable|string',
            'substrates'     => 'nullable|array',
            'specs'          => 'nullable|array',
            'thicknesses'    => 'nullable|array',
            'cover_image'    => 'nullable|string',
            'status'         => 'nullable|integer|in:0,1',
            'sort_order'     => 'nullable|integer',
            'new_image_ids'  => 'nullable|array',
        ]);

        $product->update($request->only([
            'name_zh', 'name_en', 'category_id',
            'description_zh', 'description_en',
            'substrates', 'specs', 'thicknesses',
            'cover_image', 'status', 'sort_order',
        ]));

        // Append new images
        if ($request->new_image_ids) {
            $maxSort = $product->images()->max('sort_order') ?? -1;
            foreach ($request->new_image_ids as $index => $imagePath) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'sort_order' => $maxSort + $index + 1,
                ]);
            }
        }

        return response()->json($product->load(['category', 'images']));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete physical image files
        foreach ($product->images as $image) {
            $filePath = base_path('public/uploads/' . $image->image_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $product->images()->delete();
        $product->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        $filePath = base_path('public/uploads/' . $image->image_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $image->delete();
        return response()->json(['message' => 'Image deleted']);
    }

    public function reorder(Request $request, $id)
    {
        $this->validate($request, [
            'direction' => 'required|in:up,down',
        ]);

        $product   = Product::findOrFail($id);
        $direction = $request->input('direction');

        // 1. 取全部产品（全局排序，与 adminIndex 展示顺序一致）
        $all = Product::orderBy('sort_order')->orderByDesc('created_at')->get();

        // 2. 归一化 sort_order（0,1,2,...），消除重复值
        foreach ($all as $i => $p) {
            if ($p->sort_order !== $i) {
                $p->sort_order = $i;
                $p->save();
            }
        }
        $product->refresh();

        // 3. 找相邻产品
        if ($direction === 'up') {
            $sibling = Product::where('sort_order', '<', $product->sort_order)
                ->orderByDesc('sort_order')
                ->first();
        } else {
            $sibling = Product::where('sort_order', '>', $product->sort_order)
                ->orderBy('sort_order')
                ->first();
        }

        if (!$sibling) {
            return response()->json(['message' => '已到边界'], 400);
        }

        // 4. 交换 sort_order
        $tmp                 = $product->sort_order;
        $product->sort_order = $sibling->sort_order;
        $sibling->sort_order = $tmp;
        $product->save();
        $sibling->save();

        return response()->json(['message' => 'ok']);
    }

    public function sortImages(Request $request)
    {
        $this->validate($request, [
            'images'           => 'required|array',
            'images.*.id'      => 'required|integer',
            'images.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->images as $item) {
            ProductImage::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Sorted']);
    }
}
