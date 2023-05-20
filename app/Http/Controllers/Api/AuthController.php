<?php

namespace App\Http\Controllers\Api;

use App\Dto\Request\Api\LoginDto;
use App\Dto\Response\Api\LoginSuccessDto;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(LoginDto $dto): array
    {
        /** @var User|null $user */
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => [trans('These credentials do not match our records.')],
            ]);
        }

        return (array)new LoginSuccessDto($user->createToken($dto->device)->plainTextToken);
    }

    public function logout(Request $request): Response
    {
        PersonalAccessToken::findToken($request->bearerToken())->delete();

        return response()->noContent();
    }
}
