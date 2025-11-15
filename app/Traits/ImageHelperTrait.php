<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageHelperTrait
{
    public function uploadImage($file, $folder = 'uploads')
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->storeAs($folder, $filename, 'public');

        return $folder . '/' . $filename;
    }

    public function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function getImage($path, $default = 'images/default.png')
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return asset($default);
    }
}