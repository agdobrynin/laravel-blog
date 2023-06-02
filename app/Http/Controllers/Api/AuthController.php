<?php

namespace App\Http\Controllers\Api;

use App\Dto\Request\Api\ApiLoginDto;
use App\Dto\Response\Api\ApiLoginResponseSuccessDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiDoLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Virtual\HttpApiErrorResponse;
use App\Virtual\HttpHeaderAcceptLanguage;
use App\Virtual\HttpValidationErrorResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    #[OA\Post(
        path: '/take-token',
        operationId: 'getAccessToken',
        summary: 'Get access token by login and password',
        tags: ['Authenticate']
    )]
    #[HttpHeaderAcceptLanguage]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: ApiDoLoginRequest::class)
    )]
    #[OA\Response(
        response: 200,
        description: 'Success credentials.',
        content: [new OA\JsonContent(ref: ApiLoginResponseSuccessDto::class)]
    )]
    #[HttpValidationErrorResponse]
    #[HttpApiErrorResponse(response: 400)]
    #[HttpApiErrorResponse(response: 403, description: 'Invalid credentials')]
    public function takeToken(ApiDoLoginRequest $request): JsonResponse
    {
        $dto = new ApiLoginDto(...$request->validated());
        /** @var User|null $user */
        $user = User::where('email', $dto->email)->first();

        if ($user === null || !Hash::check($dto->password, $user->password)) {
            throw new AccessDeniedHttpException(trans('These credentials do not match our records.'));
        }

        $dtoResponse = new ApiLoginResponseSuccessDto(
            token: $user->createToken($dto->device)->plainTextToken,
            device: $dto->device
        );

        return response()->json((array)$dtoResponse);
    }

    #[OA\Delete(
        path: '/invalidate-token',
        operationId: 'invalidatedAccessToken',
        description: 'Invalidate access token',
        security: [['apiKeyBearer' => []]],
        tags: ['Authenticate'],
    )]
    #[HttpHeaderAcceptLanguage]
    #[OA\Response(
        response: 204,
        description: 'Token was invalidated',
        content: [new OA\JsonContent()]
    )]
    #[HttpApiErrorResponse(response: 401, description: 'Unauthorized')]
    public function invalidateToken(Request $request): Response
    {
        PersonalAccessToken::findToken($request->bearerToken())->delete();

        return response()->noContent();
    }

    #[OA\Get(
        path: '/user',
        operationId: 'getUserInfo',
        description: 'Info about authenticated user',
        security: [['apiKeyBearer' => []]],
        tags: ['Authenticate'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Info about user',
        content: new OA\JsonContent(ref: UserResource::class),
    )]
    #[HttpApiErrorResponse(response: 401, description: 'Unauthorized')]
    public function user(Request $request): JsonResource
    {
        return new UserResource($request->user());
    }
}
