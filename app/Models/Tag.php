<?php

namespace App\Models;

use App\Enums\CacheTagsEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Cache;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function blogPosts(): MorphToMany
    {
        return $this->morphedByMany(BlogPost::class, 'taggable')->withTimestamps()->as('tagged');
    }

    public function comments(): MorphToMany
    {
        return $this->morphedByMany(Comment::class, 'taggable')->withTimestamps()->as('tagged');
    }

    public static function boot(): void
    {
        parent::boot();

        static::created(fn() => Cache::tags(CacheTagsEnum::TAGS->value)->flush());
        static::deleted(fn() => Cache::tags(CacheTagsEnum::TAGS->value)->flush());
        static::updated(fn() => Cache::tags(CacheTagsEnum::TAGS->value)->flush());
    }
}
