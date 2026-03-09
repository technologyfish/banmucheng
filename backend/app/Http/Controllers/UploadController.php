<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:jpeg,jpg,png,webp,gif|max:10240',
        ]);

        $file = $request->file('file');
        $uploadDir = base_path('public/uploads/products');

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadDir, $filename);

        $relativePath = 'products/' . $filename;
        $url = url('uploads/' . $relativePath);

        return response()->json([
            'path' => $relativePath,
            'url'  => $url,
        ]);
    }
}
