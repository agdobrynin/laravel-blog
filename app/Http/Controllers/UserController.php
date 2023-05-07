<?php

namespace App\Http\Controllers;

use App\Enums\CacheTagsEnum;
use App\Enums\StoragePathEnum;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale, User $user)
    {
        $comments = $user->commentsOn()->with(['user.image', 'tags'])
            ->withCount('tags')
            ->paginate(env('COMMENTS_PAGINATE_SIZE', 20))
            ->onEachSide(1)
            ->withQueryString();

        return view('user.show', ['user' => $user, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, User $user)
    {
        return view('user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $locale, User $user)
    {
        $user->name = $request->input('name');
        $user->save();

        if ($avatar = $request->file('avatar')) {
            $path = Storage::putFile(StoragePathEnum::USER_AVATAR->value, $avatar);

            if ($user->image) {
                $user->image->delete();
            }

            $image = new Image(['path' => $path]);
            $user->image()->save($image);

            Cache::tags(CacheTagsEnum::MOST_ACTIVE_BLOGGERS->value)->flush();
        }

        return redirect()
            ->route('users.show', $user)
            ->with('success', trans('Пользователь обновлен.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, User $user)
    {
        //
    }
}
