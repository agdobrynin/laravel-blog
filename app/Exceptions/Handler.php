<?php

namespace App\Exceptions;

use App\Dto\Response\Api\ApiErrorResponseDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() && $e->getPrevious() instanceof ModelNotFoundException) {
                $file = null;
                $line = null;

                if (App::isLocal()) {
                    $file = $e->getFile();
                    $line = $e->getLine();
                }

                $error = new ApiErrorResponseDto(trans('Запись не найдена'), $e::class, $file, $line);

                return response()->json((array)$error, Response::HTTP_NOT_FOUND);
            }
        });
    }
}
