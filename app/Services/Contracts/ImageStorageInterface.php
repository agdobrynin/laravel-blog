<?php

namespace App\Services\Contracts;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

interface ImageStorageInterface
{
    public const THUMB_PREFIX = '_thumb';

    public function fileSystem(): Filesystem;

    public function url(string $path): ?string;

    public function thumbUrl(string $path, int $width, ?int $height = null): ?string;

    /**
     * Generate thumbnail path for original image path
     */
    public function thumbFilePath(string $path, int $width, ?int $height = null): string;

    public function thumbClear(?string $path): void;

    public function delete(string $path): void;

    public function putFile(File|string|UploadedFile $path): string;
}
