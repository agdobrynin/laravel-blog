<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    public function blogPost(): MorphOne
    {
        return $this->morphOne(BlogPost::class, 'imageable');
    }

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'imageable');
    }

    public function origUrl(): ?string
    {
        return Storage::has($this->path) ? Storage::url($this->path) : null;
    }

    public function thumbUrl(): ?string
    {
        return $this->path_thumb && Storage::has($this->path_thumb)
            ? Storage::url($this->path_thumb)
            : null;
    }

    public function fullOrigPath(): ?string
    {
        return Storage::has($this->path) ? Storage::path($this->path) : null;
    }

    public static function boot()
    {
        parent::boot();

        self::deleted(fn (Image $model) => Storage::delete($model->path));
    }
}
