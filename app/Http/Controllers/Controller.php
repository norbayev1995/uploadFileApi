<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function uploadFile($file, $path = "uploads")
    {
        $photoName = md5(time() . $file->getFilename()) . "." . $file->getClientOriginalExtension();
        return $file->storeAs($path, $photoName, 'public');
    }

    public function deletePhoto($path)
    {
        $fullPath = storage_path("app/public/" . $path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
