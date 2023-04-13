<?php

namespace App\Models;

use App\Dto\BlogPostFilterDto;
use App\Enums\OrderBlogPostEnum;
use App\Scopes\LatestCreatedScope;
use App\Scopes\ShowDeletedForAdminRoleScope;
use App\Services\Contracts\MostActiveBloggersInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $with = [
        // 'user.roles'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latestUpdated();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMostCommented(Builder $builder)
    {
        return $this->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeFilter(Builder $builder, BlogPostFilterDto $dto): Builder
    {
        $builder->with('user')->withCount('comments');

        return $builder
            ->when(
                $dto->order === OrderBlogPostEnum::MOST_COMMENTED,
                fn(Builder $query, $value) => $query->orderBy('comments_count', 'desc')
            );
    }

    public static function boot(): void
    {
        static::addGlobalScope(new ShowDeletedForAdminRoleScope());
        parent::boot();
        static::addGlobalScope(new LatestCreatedScope());

        static::created(function (self $post) {
            Cache::forget(MostActiveBloggersInterface::class);
        });

        static::deleted(function (self $post) {
            Cache::forget(MostActiveBloggersInterface::class);
            $post->comments()->delete();
        });

        static::restoring(function (self $post) {
            Cache::forget(MostActiveBloggersInterface::class);
            $post->comments()->withTrashed()->where('deleted_at', '>=', $post->deleted_at)->restore();
        });
    }
}
