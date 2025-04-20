<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class FileUploadHelper
{
    /**
     * Validuje a uloží súbor.
     *
     * @param  UploadedFile  $file
     * @param  string  $directory
     * @return string
     * @throws ValidationException
     */
    public static function uploadFile(UploadedFile $file, $directory = 'uploads')
    {
        // Validácia typu súboru
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 10 * 1024 * 1024; // 10 MB

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new ValidationException('Nepodporovaný typ súboru.');
        }

        if ($file->getSize() > $maxSize) {
            throw new ValidationException('Súbor je príliš veľký. Maximálna veľkosť je 10 MB.');
        }

        // Uloženie súboru na disk
        $path = $file->store($directory, 'public');

        return $path;
    }

    /**
     * Získa úplnú cestu k súboru.
     *
     * @param  string  $path
     * @return string
     */
    public static function getFilePath($path)
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Odstráni súbor zo systému.
     *
     * @param  string  $path
     * @return bool
     */
    public static function deleteFile($path)
    {
        return Storage::disk('public')->delete($path);
    }
}
