<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasComments
{

    public static function bootHasComments(): void
    {
        if (\in_array(SoftDeletes::class, class_uses(self::class))) {
            static::deleted(fn($model) => $model->commentsOn()->delete());

            static::restoring(fn($model) => $model->commentsOn()
                ->withTrashed()
                ->where('deleted_at', '>=', $model->{$model->getDeletedAtColumn()})->restore());
        }
    }

    public function commentsOn(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
