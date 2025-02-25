<?php

declare(strict_types=1);

namespace App\Core\Http;

use Laminas\Diactoros\Response\JsonResponse;

class Response
{
    public static function json(array $data, int $status = 200): JsonResponse
    {
        return new JsonResponse($data, $status);
    }

    public static function error(string $message, int $status = 400): JsonResponse
    {
        return new JsonResponse([
            'error' => [
                'message' => $message
            ]
        ], $status);
    }

    public static function success(array $data, string $message = ''): JsonResponse
    {
        return new JsonResponse([
            'data' => $data,
            'message' => $message
        ], 200);
    }
}
