<?php

namespace App\Models;

use App\Models\Traits\HasTagged;
use App\Scopes\LatestCreatedScope;
use App\Scopes\ShowDeletedForAdminRoleScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes, HasTagged;

    protected $fillable = ['content', 'user_id'];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function boot(): void
    {
        static::addGlobalScope(new ShowDeletedForAdminRoleScope());
        parent::boot();
        static::addGlobalScope(new LatestCreatedScope());
    }
}
