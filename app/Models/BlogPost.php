<?php

namespace App\Models;

use App\Dto\BlogPostFilterDto;
use App\Enums\CacheTagsEnum;
use App\Enums\OrderBlogPostEnum;
use App\Scopes\LatestCreatedScope;
use App\Scopes\ShowDeletedForAdminRoleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps()->as('tagged');
    }

    public function scopeMostCommented(Builder $builder)
    {
        return $this->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeFilter(Builder $builder, BlogPostFilterDto $dto): Builder
    {
        return $builder
            ->with(['user', 'tags'])
            ->withCount('comments')
            ->when(
                $dto->order === OrderBlogPostEnum::MOST_COMMENTED,
                fn(Builder $query, $value) => $query->orderBy('comments_count', 'desc')
            )
            ->when(
                $dto->tag,
                function (Builder $query, $value) {
                    $query->whereHas('tags', fn(Builder $query) => $query->where('tags.id', $value->id));
                }
            );
    }

    public static function boot(): void
    {
        static::addGlobalScope(new ShowDeletedForAdminRoleScope());
        parent::boot();
        static::addGlobalScope(new LatestCreatedScope());

        static::created(fn() => Cache::tags(CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value)->flush());

        static::deleted(function (self $post) {
            Cache::tags(CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value)->flush();
            $post->comments()->delete();
        });

        static::restoring(function (self $post) {
            Cache::tags(CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value)->flush();
            $post->comments()->withTrashed()->where('deleted_at', '>=', $post->deleted_at)->restore();
        });
    }
}
