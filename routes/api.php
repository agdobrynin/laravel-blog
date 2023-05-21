<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\PostCommentController;
use App\Services\LocaleByHttpHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/** @var LocaleByHttpHeader $localeByHeader */
$localeByHeader = App::make(LocaleByHttpHeader::class);
App::setLocale($localeByHeader->locale);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::delete('logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('api.logout');

Route::prefix('v1')
    ->name('api.v1.')
    ->group(static function () {
        Route::apiResource('posts.comments', PostCommentController::class);
    });
