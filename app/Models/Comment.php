<?php

namespace App\Models;

use App\Scopes\ShowDeletedForAdminRoleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['content', 'user_id'];

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatestUpdated(Builder $builder): Builder
    {
        return $builder->orderBy(static::UPDATED_AT, 'desc');
    }

    public static function boot(): void
    {
        static::addGlobalScope(new ShowDeletedForAdminRoleScope());

        parent::boot();
    }
}
