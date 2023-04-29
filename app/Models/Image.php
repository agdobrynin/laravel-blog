<?php

namespace App\Models;

use App\Enums\StoragePathEnum;
use App\Jobs\ImageDelete;
use App\Jobs\ImageResizerAvatar;
use App\Jobs\ImageResizerBlogPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function url(): ?string
    {
        return Storage::has($this->path) ? Storage::url($this->path) : null;
    }

    public function thumbFile(int $width): string
    {
        $this->checkThumbnailType();
        $this->checkThumbnailWidth($width);

        $info = pathinfo($this->path);

        $directory = $this->imageable_type === User::class
            ? StoragePathEnum::USER_AVATAR_THUMB->value
            : StoragePathEnum::POST_IMAGE_THUMB->value;

        return $directory . DIRECTORY_SEPARATOR . sprintf(
                '%s%s%s',
                $info['filename'],
                $width ? '_w_' . $width : '',
                $info['extension'] ? '.' . $info['extension'] : ''
            );
    }

    public function thumbUrl(int $width): string
    {
        $this->checkThumbnailType();
        $this->checkThumbnailWidth($width);

        $thumbFile = $this->thumbFile($width);

        if (Storage::has($thumbFile)) {
            return Storage::url($thumbFile);
        }

        if ($this->imageable_type === User::class) {
            ImageResizerAvatar::dispatch($this, $width);
        } else {
            ImageResizerBlogPost::dispatch($this, $width);
        }

        return $this->url();
    }

    public function fullOrigPath(): ?string
    {
        return Storage::has($this->path) ? Storage::path($this->path) : null;
    }

    public static function boot()
    {
        parent::boot();

        self::deleted(fn(Image $model) => ImageDelete::dispatch($model->imageable_type, $model->path));
    }

    /**
     * @throws \Throwable
     */
    protected function checkThumbnailWidth($width): void
    {
        throw_if(
            $width < 1,
            message: trans('Ширина изображения должна быть более нуля. Получено значение ":width"', ['width' => $width])
        );
    }

    /**
     * @throws \Throwable
     */
    protected function checkThumbnailType(): void
    {
        throw_if(
            !in_array($this->imageable_type, [User::class, BlogPost::class]),
            message: trans('Не поддерживаемый тип изображения для миниатюры. Получено :type', ['type' => $this->imageable_type])
    );
    }
}
