<?php

namespace App\Http\Controllers\Api;

use App\Dto\Request\Api\ApiLoginDto;
use App\Dto\Response\Api\ApiLoginResponseSuccessDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiDoLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(ApiDoLoginRequest $request): JsonResponse
    {
        $dto = new ApiLoginDto(...$request->validated());
        /** @var User|null $user */
        $user = User::where('email', $dto->email)->first();

        if ($user === null || !Hash::check($dto->password, $user->password)) {
            throw new AccessDeniedHttpException(trans('These credentials do not match our records.'));
        }

        return response()->json(
            (array)new ApiLoginResponseSuccessDto($user->createToken($dto->device)->plainTextToken)
        );
    }

    public function logout(Request $request): Response
    {
        PersonalAccessToken::findToken($request->bearerToken())->delete();

        return response()->noContent();
    }
}
