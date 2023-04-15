<?php

namespace App\Models;

use App\Enums\CacheTagsEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function blogPosts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class)->withTimestamps()->as('tagged');
    }

    public static function boot(): void
    {
        parent::boot();

        static::created(fn() => Cache::tags(CacheTagsEnum::TAGS->value)->flush());
        static::deleted(fn() => Cache::tags(CacheTagsEnum::TAGS->value)->flush());
        static::updated(fn() => Cache::tags(CacheTagsEnum::TAGS->value)->flush());
    }
}
