<?php

namespace App\Services;

class UploadService
{
    /**
     * Methode pour gérer l'upload de fichiers.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string
     */
    public function uploadFile($file, string $path): string
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path($path), $filename);
        
        return $path . '/' . $filename;
    }
}