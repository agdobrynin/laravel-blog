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

    public function url(): string
    {
        return Storage::url($this->path);
    }

    public function fullPath(): string
    {
        return Storage::path($this->path);
    }
}
