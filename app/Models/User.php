<?php

namespace App\Models;

use App\Enums\CacheTagsEnum;
use App\Enums\RolesEnum;
use App\Models\Traits\HasComments;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasComments;

    public const ROLE_CACHE_PREFIX_KEY = 'user-roles:';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function preference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')
            ->withTimestamps();
    }

    public function getRoles()
    {
        if (config('roles.cache.enabled')) {
            return Cache::tags(CacheTagsEnum::USER_GROUP->value)
                ->remember(
                    self::ROLE_CACHE_PREFIX_KEY . $this->id,
                    config('roles.cache.ttl'),
                    fn() => $this->roles()->get()
                );
        }

        return $this->roles()->get();
    }

    /**
     * Check user role by role model or role slug as string.
     */
    public function hasRole(Role|RolesEnum $role): bool
    {
        if ($role instanceof RolesEnum) {
            return $this->getRoles()->contains(static function ($value) use ($role) {
                return Str::is($role->value, $value->slug);
            });
        }

        return $this->getRoles()->contains($role);
    }

    public function attachRole(Role $role): self
    {
        if (!$this->getRoles()->contains($role)) {
            $this->clearCache();
            $this->roles()->attach($role);
        }

        return $this;
    }

    public function detachRole(Role $role): int
    {
        $this->clearCache();

        return $this->roles()->detach($role);
    }

    public function detachAllRoles(): int
    {
        $this->clearCache();

        return $this->roles()->detach();
    }

    public function syncRoles(array|Collection $roles): array
    {
        $this->clearCache();

        return $this->roles()->sync($roles);
    }

    public function scopeWithMostBlogPostLastMonth(Builder $builder, ?int $lastMonth = null, ?int $minCountPost = null): Builder
    {
        return $builder
            ->with('image')
            ->when($lastMonth, function (Builder $query, int $value) {
                return $query->withCount([
                    'blogPosts' => function (Builder $query) use ($value) {
                        return $query->whereBetween(static::CREATED_AT, [now()->subMonths($value), now()]);
                    }
                ]);
            })
            ->when(!$lastMonth, function (Builder $query) {
                return $query->withCount('blogPosts');
            })
            ->when($minCountPost, function (Builder $query, int $value) {
                return $query->having('blog_posts_count', '>=', $value);
            })
            ->orderBy('blog_posts_count', 'desc');
    }

    public function scopeUsersCommentable(Builder $builder, $commentable): Builder
    {
        return $builder->whereHas(
            'comments',
            fn($q) => $q->where('commentable_id', $commentable->id)
                ->where('commentable_type', get_class($commentable))
        );
    }

    private function clearCache(): void
    {
        if (config('roles.cache.enabled')) {
            Cache::forget(self::ROLE_CACHE_PREFIX_KEY . $this->id);
        }
    }
}
