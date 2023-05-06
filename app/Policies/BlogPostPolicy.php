<?php

namespace App\Policies;

use App\Enums\RolesEnum;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPostPolicy
{
    /**
     * Policy filter before
     */
    public function before(User $user, string $ability): bool|null
    {
        if($user->hasRole(RolesEnum::ADMIN)) {
            return true;
        }
        // TODO make roles and permission with spatie/laravel-permission

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, BlogPost $blogPost): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BlogPost $blogPost): Response
    {
        return $this->checkIsOwnerAndResponse($user, $blogPost);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BlogPost $blogPost): Response
    {
        return $this->checkIsOwnerAndResponse($user, $blogPost);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BlogPost $blogPost): Response
    {
        return $this->checkIsOwnerAndResponse($user, $blogPost);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BlogPost $blogPost): Response
    {
        return $this->checkIsOwnerAndResponse($user, $blogPost);
    }

    private function checkIsOwnerAndResponse(User $user, BlogPost $post): Response
    {
        return $user->id === $post->user_id
            ? Response::allow()
            : Response::deny(trans('Вы не являетесь владельцем этого поста'));
    }
}
