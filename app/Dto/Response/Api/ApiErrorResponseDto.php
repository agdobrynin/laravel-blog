<?php
declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[OA\Schema(title: 'Response for error')]
readonly class ApiErrorResponseDto
{
    public function __construct(
        #[OA\Property(example: 'Description of what happened')]
        public string $message,
        #[OA\Property(example: NotFoundHttpException::class)]
        public string $exception,
        public ?string $file = null,
        public ?int $line = null,
    )
    {
    }
}
