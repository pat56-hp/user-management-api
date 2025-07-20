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

        logger()->info('File uploaded successfully', [
            'path' => $path,
            'filename' => $filename,
        ]);
        
        return $path . '/' . $filename;
    }
}