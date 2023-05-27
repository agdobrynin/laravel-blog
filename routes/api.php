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

Route::post('take-token', [AuthController::class, 'takeToken'])->name('api.login');

Route::middleware('auth:sanctum')->group(function() {
    Route::delete('invalidate-token', [AuthController::class, 'invalidateToken'])
        ->name('api.logout');
    Route::get('user', [AuthController::class, 'user'])
        ->name('api.user');
});

Route::prefix('v1')
    ->name('api.v1.')
    ->middleware(['auth:sanctum', 'verified'])
    ->group(static function () {
        Route::apiResource('posts.comments', PostCommentController::class);
    });
