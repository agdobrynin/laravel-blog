<?php
declare(strict_types=1);

namespace App\Scopes;

use App\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ShowDeletedForAdminRoleScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::user()?->hasRole(RolesEnum::ADMIN)) {
            // $builder->withTrashed();
            $builder->withoutGlobalScope(SoftDeletingScope::class);
        }
    }
}
