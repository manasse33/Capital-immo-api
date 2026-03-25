<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk(config('filesystems.default', 'local'));
    }

    public function upload(UploadedFile $file, string $directory = 'images'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $filename;

        // Pour Cloudinary
        if (config('filesystems.default') === 'cloudinary') {
            $result = $file->storeOnCloudinary($directory);
            return $result->getSecurePath();
        }

        // Stockage local ou S3
        $file->storeAs($directory, $filename, 'public');

        return '/storage/' . $path;
    }

    public function uploadMultiple(array $files, string $directory = 'images'): array
    {
        $urls = [];
        foreach ($files as $file) {
            $urls[] = $this->upload($file, $directory);
        }
        return $urls;
    }

    public function delete(string $url): bool
    {
        // Si c'est une URL Cloudinary
        if (str_contains($url, 'cloudinary')) {
            // Logique de suppression Cloudinary
            return true;
        }

        // Suppression du stockage local
        $path = str_replace('/storage/', '', $url);
        return $this->disk->delete($path);
    }

    public function getUrl(string $path): string
    {
        return $this->disk->url($path);
    }
}
