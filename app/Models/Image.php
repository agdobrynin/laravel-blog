<?php

namespace App\Models;

use App\Services\Contracts\AvatarImageStorageInterface;
use App\Services\Contracts\BlogPostImageStorageInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\App;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    public static function boot(): void
    {
        parent::boot();

        self::deleted(static function (Image $model) {
            $storage = match ($model->imageable_type) {
                User::class => App::make(AvatarImageStorageInterface::class),
                BlogPost::class => App::make(BlogPostImageStorageInterface::class),
                default => null,
            };

            if ($storage) {
                $storage->delete($model->path);
            }
        });
    }

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
