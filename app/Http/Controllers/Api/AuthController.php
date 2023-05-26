<?php

namespace App\Http\Controllers\Api;

use App\Dto\Request\Api\ApiLoginDto;
use App\Dto\Response\Api\ApiErrorResponseDto;
use App\Dto\Response\Api\ApiLoginResponseSuccessDto;
use App\Dto\Response\Api\ApiValidationDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiDoLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use OpenApi\Attributes as SWG;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    #[SWG\Post(
        path: '/login',
        operationId: 'getAccessToken',
        summary: 'Get access token by login and password',
        requestBody: new SWG\RequestBody(
            required: true,
            content: new SWG\JsonContent(
                ref: ApiLoginDto::class,
            ),
        ),
        tags: ['Authenticate']
    )]
    #[SWG\Response(
        response: ResponseAlias::HTTP_OK,
        description: 'Success credentials. Return access Bearer token.',
        content: [new SWG\JsonContent(ref: ApiLoginResponseSuccessDto::class)]
    )]
    #[SWG\Response(
        response: ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
        description: 'Validation errors',
        content: [new SWG\JsonContent(ref: ApiValidationDto::class)]
    )]
    #[SWG\Response(
        response: ResponseAlias::HTTP_BAD_REQUEST,
        description: 'Errors',
        content: [new SWG\JsonContent(ref: ApiErrorResponseDto::class)]
    )]
    #[SWG\Response(
        response: ResponseAlias::HTTP_FORBIDDEN,
        description: 'Validation errors',
        content: [new SWG\JsonContent(ref: ApiErrorResponseDto::class)]
    )]
    public function login(ApiDoLoginRequest $request): JsonResponse
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

    #[SWG\Delete(
        path: '/invalidate-token',
        operationId: 'invalidatedAccessToken',
        description: 'Invalidate access token',
        security: [['apiKeyBearer' => []]],
        tags: ['Authenticate'],
    )]
    #[SWG\Response(
        response: ResponseAlias::HTTP_NO_CONTENT,
        description: 'Access token was invalidated',
        content: [new SWG\JsonContent()],
    )]
    #[SWG\Response(
        response: ResponseAlias::HTTP_UNAUTHORIZED,
        description: 'Error message',
        content: [new SWG\JsonContent(ref: ApiErrorResponseDto::class)]
    )]
    public function invalidateToken(Request $request): Response
    {
        PersonalAccessToken::findToken($request->bearerToken())->delete();

        return response()->noContent();
    }
}
