<?php
declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class LatestUpdatedScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy(Model::UPDATED_AT, 'desc');
    }
}
