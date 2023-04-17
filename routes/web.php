<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('home.index');
})->name('home.index');

Route::resource('posts', BlogPostController::class);
Route::put('posts/{post}/restore', [BlogPostController::class, 'restore'])
    ->name('posts.restore')
    ->withTrashed();

Route::resource('posts.comments', PostCommentController::class)
    ->only(['store']);

Route::resource('users', UserController::class)
    ->only(['show', 'edit', 'update']);

Route::middleware('auth')
    ->name('verification.')
    ->prefix('/email')
    ->group(static function () {

        Route::get('/verify', fn () => view('auth.verify'))->name('notice');

        Route::post('/verification-notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();

            return back()
                ->with('success', trans('Ссылка подтверждения успешно отправлена!'));
        })->middleware(['throttle:6,1'])->name('send');

        Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();

            return redirect()->route('home.index')
                ->with('success', trans('Ваш email успешно подтвержен'));
        })->middleware(['signed'])->name('verify');
    });
