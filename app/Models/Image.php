<?php

namespace App\Models;

use App\Enums\StoragePathEnum;
use App\Jobs\ImageDelete;
use App\Jobs\ImageResizerAvatar;
use App\Jobs\ImageResizerBlogPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function origUrl(): ?string
    {
        return Storage::has($this->path) ? Storage::url($this->path) : null;
    }

    public function thumbFile(?int $width = null): ?string
    {
        if ($this->path && $width && \in_array($this->imageable_type, [User::class, BlogPost::class])) {
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

        return null;
    }

    public function thumbUrl(?int $width = null): string
    {
        if ($thumbFile = $this->thumbFile($width)) {
            if (Storage::has($thumbFile)) {
                return Storage::url($thumbFile);
            }

            if ($this->imageable_type === User::class) {
                ImageResizerAvatar::dispatch($this, $width);
            } else {
                ImageResizerBlogPost::dispatch($this, $width);
            }
        }

        return $this->origUrl();
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
}
