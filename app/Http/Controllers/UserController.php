<?php

namespace App\Http\Controllers;

use App\Dto\Request\UserProfileDto;
use App\Enums\CacheTagsEnum;
use App\Http\Requests\UserProfileRequest;
use App\Models\Image;
use App\Models\User;
use App\Models\UserPreference;
use App\Services\Contracts\AvatarImageStorageInterface;
use App\Services\Contracts\ReadNowObjectInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale, User $user, ReadNowObjectInterface $readNowObject): View
    {
        $comments = $user->commentsOn()->with(['user.image', 'tags'])
            ->withCount('tags')
            ->paginate(env('COMMENTS_PAGINATE_SIZE', 20))
            ->onEachSide(1)
            ->withQueryString();

        return view(
            'user.show',
            [
                'user' => $user,
                'comments' => $comments,
                'readNowCount' => $readNowObject->readNowCount($user, session()->getId())
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, User $user): View
    {
        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        string                      $locale,
        User                        $user,
        UserProfileRequest          $request,
        AvatarImageStorageInterface $storage
    ): RedirectResponse
    {
        $dto = new UserProfileDto(...$request->validated());

        $user->name = $dto->name;
        $user->save();
        App::setLocale($dto->locale->value);

        if ($user->preference) {
            $user->preference->locale = $dto->locale->value;
            $user->push();
        } else {
            $user->preference()->save(new UserPreference(['locale' => $dto->locale->value]));
        }

        if ($avatar = $dto->avatar) {
            $path = $storage->putFile($avatar);

            if ($user->image) {
                $user->image->delete();
            }

            $image = new Image(['path' => $path]);
            $user->image()->save($image);

            Cache::tags(CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value)->flush();
        }

        return redirect()
            ->route('users.show', ['user' => $user, 'locale' => $dto->locale->value])
            ->with('success', trans('Пользователь обновлен.'));
    }
}
