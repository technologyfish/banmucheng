<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    // 最大输出宽/高（px），超过则等比缩放
    const MAX_DIMENSION = 1920;
    // JPEG/WebP 压缩质量 0-100
    const IMAGE_QUALITY = 82;

    public function upload(Request $request)
    {
        // 运行时放宽 PHP 限制，应对大图上传
        @ini_set('max_execution_time', '120');
        @ini_set('memory_limit', '256M');

        $this->validate($request, [
            'file' => 'required|file|mimes:jpeg,jpg,png,webp,gif|max:10240',
        ]);

        $file = $request->file('file');
        $uploadDir = base_path('public/uploads/products');

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 小于 500KB 的图片跳过 GD，直接移动，避免无谓的解码/重编码开销
        $needCompress = $file->getSize() >= 500 * 1024 && extension_loaded('gd');

        if ($needCompress) {
            $filename = time() . '_' . uniqid() . '.jpg';
            $destPath = $uploadDir . '/' . $filename;
            $this->compressAndSave($file->getPathname(), $destPath, $file->getMimeType());
        } else {
            $ext      = strtolower($file->getClientOriginalExtension()) ?: 'jpg';
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $file->move($uploadDir, $filename);
        }

        $relativePath = 'products/' . $filename;
        $url = url('uploads/' . $relativePath);

        return response()->json([
            'path' => $relativePath,
            'url'  => $url,
        ]);
    }

    /**
     * 使用 GD 将原图等比缩放后压缩保存为 JPEG
     */
    private function compressAndSave(string $srcPath, string $destPath, string $mime): void
    {
        // 如果 GD 不可用，直接复制原文件
        if (!extension_loaded('gd')) {
            copy($srcPath, $destPath);
            return;
        }

        // 根据 MIME 创建 GD 图像资源
        $src = match (true) {
            str_contains($mime, 'png')  => @imagecreatefrompng($srcPath),
            str_contains($mime, 'gif')  => @imagecreatefromgif($srcPath),
            str_contains($mime, 'webp') => @imagecreatefromwebp($srcPath),
            default                      => @imagecreatefromjpeg($srcPath),
        };

        if (!$src) {
            // GD 无法解析时直接复制
            copy($srcPath, $destPath);
            return;
        }

        [$origW, $origH] = [imagesx($src), imagesy($src)];

        // 计算等比缩放后的尺寸
        $ratio  = min(self::MAX_DIMENSION / $origW, self::MAX_DIMENSION / $origH, 1.0);
        $newW   = (int) round($origW * $ratio);
        $newH   = (int) round($origH * $ratio);

        // 创建目标画布（truecolor 支持完整色彩）
        $dst = imagecreatetruecolor($newW, $newH);

        // PNG/GIF 透明背景处理：填白色再混合，避免黑底
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $white);
        imagealphablending($dst, true);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        imagejpeg($dst, $destPath, self::IMAGE_QUALITY);

        imagedestroy($src);
        imagedestroy($dst);
    }
}
