<?php

namespace App\Http\Controllers;

use App\Models\ProductOption;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * GET /api/admin/options  →  { substrate: [...], spec: [...], thickness: [...] }
     */
    public function index()
    {
        $options = ProductOption::orderBy('sort_order')->orderBy('id')->get();
        $grouped = $options->groupBy('type');
        return response()->json($grouped);
    }

    /**
     * GET /api/options  →  public version (active only)
     */
    public function publicIndex()
    {
        $options = ProductOption::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        return response()->json($options->groupBy('type'));
    }

    /**
     * POST /api/admin/options
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'type'       => 'required|in:substrate,spec,thickness',
            'value_zh'   => 'required|string|max:100',
            'value_en'   => 'required|string|max:100',
            'sort_order' => 'integer',
        ]);

        $option = ProductOption::create([
            'type'       => $request->input('type'),
            'value_zh'   => $request->input('value_zh'),
            'value_en'   => $request->input('value_en'),
            'sort_order' => $request->input('sort_order', 0),
            'is_active'  => true,
        ]);

        return response()->json($option, 201);
    }

    /**
     * PUT /api/admin/options/{id}
     */
    public function update(Request $request, $id)
    {
        $option = ProductOption::findOrFail($id);

        $this->validate($request, [
            'value_zh'   => 'required|string|max:100',
            'value_en'   => 'required|string|max:100',
            'sort_order' => 'integer',
        ]);

        $option->update($request->only(['value_zh', 'value_en', 'sort_order', 'is_active']));

        return response()->json($option);
    }

    /**
     * DELETE /api/admin/options/{id}
     */
    public function destroy($id)
    {
        $option = ProductOption::findOrFail($id);
        $option->delete();
        return response()->json(['message' => 'deleted']);
    }
}
