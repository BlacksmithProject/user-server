<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ErrorJsonResponse
{
    public static function format(string $message, int $httpCode): JsonResponse
    {
        return new JsonResponse([
            'error' => $message,
        ], $httpCode);
    }
}
