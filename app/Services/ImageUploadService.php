<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    protected $disk;
    protected $diskName;

    public function __construct()
    {
        $configured = config('filesystems.default', 'local');
        $this->diskName = $configured === 'local' ? 'public' : $configured;
        $this->disk = Storage::disk($this->diskName);
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
        if (!$this->disk->exists($directory)) {
            $this->disk->makeDirectory($directory);
        }
        $file->storeAs($directory, $filename, $this->diskName);

        return $this->disk->url($path);
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
        $path = $this->extractPathFromUrl($url);
        return $this->disk->delete($path);
    }

    public function getUrl(string $path): string
    {
        return $this->disk->url($path);
    }

    protected function extractPathFromUrl(string $url): string
    {
        $path = $url;
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            $parsed = parse_url($url);
            $path = $parsed['path'] ?? $url;
        }

        $marker = '/storage/';
        $pos = strpos($path, $marker);
        if ($pos !== false) {
            $path = substr($path, $pos + strlen($marker));
        }

        return ltrim($path, '/');
    }
}
