<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')
    ->name('api.v1.')
    ->group(static function () {
        /** @var LocaleByHttpHeader $localeByHeader */
        $localeByHeader = App::make(LocaleByHttpHeader::class);
        App::setLocale($localeByHeader->locale);

        Route::apiResource('posts.comments', PostCommentController::class);
    });
