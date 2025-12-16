<?php

namespace App\Services;

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected static bool $configured = false;

    protected static function configure(): void
    {
        if (self::$configured) {
            return;
        }

        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);

        self::$configured = true;
    }


// ⁡⁣⁣⁢=====================================================================⁡

public static function uploadImage(TemporaryUploadedFile|UploadedFile $file, string $folder = 'general'): array
{
    self::configure();

    try {
        // Pastikan dapat path
        $path = $file->getRealPath();
        if (! $path || ! file_exists($path)) {
            throw new \RuntimeException('File path invalid or not exists: ' . (string) $path);
        }

        Log::debug('CloudinaryService: uploading file', [
            'path' => $path,
            'folder' => $folder,
            'file_class' => get_class($file),
        ]);

        $upload = (new UploadApi())->upload(
            $path,
            ['folder' => $folder]
        );

        Log::debug('CloudinaryService: upload finished', ['upload' => $upload]);

        return [
            'url' => $upload['secure_url'] ?? $upload['url'] ?? null,
            'public_id' => $upload['public_id'] ?? null,
        ];
    } catch (\Throwable $e) {
        Log::error('CloudinaryService: uploadImage failed', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        throw $e; // biarkan naik supaya mutator bisa catch-nya
    }
}
// ⁡⁣⁣⁢=====================================================================⁡


    // public static function uploadImage(TemporaryUploadedFile $file, string $folder = 'general'): array
    // {
    //     self::configure();

    //     $upload = (new UploadApi())->upload(
    //         $file->getRealPath(),
    //         ['folder' => $folder]
    //     );

    //     return [
    //         'url'       => $upload['secure_url'] ?? $upload['url'],
    //         'public_id' => $upload['public_id'],
    //     ];
    // }

    public static function deleteImage(?string $publicId): void
    {
        if (! $publicId) {
            return;
        }

        self::configure();

        (new UploadApi())->destroy($publicId);
    }
}
