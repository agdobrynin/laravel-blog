<?php

use App\Http\Controllers\BlogPostController;
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

Route::resource('post', BlogPostController::class);

Route::middleware('auth')
    ->name('verification.')
    ->prefix('/email')
    ->group(static function () {

        Route::get('/verify', fn () => view('auth.verify'))->name('notice');

        Route::post('/verification-notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();

            return back()->with('message', 'Verification link sent!');
        })->middleware(['throttle:6,1'])->name('send');

        Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();

            return redirect()->route('home.index')->with('success', 'Your email was verified');
        })->middleware(['signed'])->name('verify');
    });
