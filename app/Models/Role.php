<?php

namespace App\Models;

use App\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'name'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id')->withTimestamps();
    }

    public function usersWithTrashed(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id')->withTrashed()->withTimestamps();
    }

    public static function create(string $name, RolesEnum $rolesEnum): self
    {
        $instance = new self();
        $instance->name = $name;
        $instance->slug = $rolesEnum->value;

        $instance->save();

        return $instance;
    }
}
