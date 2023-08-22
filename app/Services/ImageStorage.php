<?php

namespace App\Services;

use App\Services\Contracts\AvatarImageStorageInterface;
use App\Services\Contracts\BlogPostImageStorageInterface;
use App\Services\Contracts\ImageStorageInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

readonly class ImageStorage implements ImageStorageInterface, AvatarImageStorageInterface, BlogPostImageStorageInterface
{
    public function __construct(
        protected Filesystem $filesystem,
        protected string     $resizeEventClass,
        protected Dispatcher $dispatcher,
    )
    {
    }

    public function fileSystem(): Filesystem
    {
        return $this->filesystem;
    }

    public function putFile(File|string|UploadedFile $path): string
    {
        return $this->filesystem->putFile($path);
    }

    public function thumbUrl(string $path, int $width, ?int $height = null): ?string
    {
        if (!$this->filesystem->exists($path)) {
            return null;
        }

        $thumbPath = $this->thumbFilePath($path, $width);

        if ($this->filesystem->exists($thumbPath)) {
            return $this->filesystem->url($thumbPath);
        }

        $this->dispatcher->dispatch(new $this->resizeEventClass($path, $width, null));

        return $this->filesystem->url($path);
    }

    public function thumbFilePath(string $path, ?int $width = null, ?int $height = null): string
    {
        ['extension' => $extension, 'filename' => $filename] = pathinfo($path);

        return sprintf(
            '%s%s%s%s%s%s',
            $this->thumbDirectory($path),
            DIRECTORY_SEPARATOR,
            $filename,
            $width ? '_w_' . $width : '',
            $height ? '_h_' . $height : '',
            $extension ? '.' . $extension : ''
        );
    }

    protected function thumbDirectory(?string $path = null): string
    {
        if ($path) {
            ['filename' => $filename] = pathinfo($path);
        } else {
            $filename = '';
        }

        return sprintf(
            '%s%s%s',
            self::THUMB_PREFIX,
            DIRECTORY_SEPARATOR,
            $filename,
        );
    }

    public function url(string $path): ?string
    {
        return $this->filesystem->exists($path) ? $this->filesystem->url($path) : null;
    }

    public function thumbClear(?string $path): void
    {
        $dir = $this->thumbDirectory($path);

        if ($this->filesystem->directoryExists($dir)) {
            $this->filesystem->deleteDirectory($dir);
        }
    }

    public function delete(string $path): void
    {
        if ($this->filesystem->exists($path)) {
            $this->filesystem->delete($path);
            $this->filesystem->deleteDirectory($this->thumbDirectory($path));
        }
    }
}
