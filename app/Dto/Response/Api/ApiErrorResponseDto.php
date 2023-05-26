<?php
declare(strict_types=1);

namespace App\Dto\Response\Api;

use OpenApi\Attributes as SWG;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[SWG\Schema(
    schema: 'ApiErrorResponseDto',
    title: 'Response for error',
)]
readonly class ApiErrorResponseDto
{
    public function __construct(
        #[SWG\Property(example: 'Description of what happened')]
        public string $message,
        #[SWG\Property(example: NotFoundHttpException::class)]
        public string $exception,
        public ?string $file = null,
        public ?int $line = null,
    )
    {
    }
}
