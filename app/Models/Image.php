<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'blog_post_id'];

    public function blogPost(): HasOne
    {
        return $this->hasOne(BlogPost::class);
    }

    public function url(): string
    {
        return Storage::url($this->path);
    }
}
