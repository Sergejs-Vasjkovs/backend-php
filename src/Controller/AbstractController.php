<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Http\Request;
use App\Core\Http\Response;
use Laminas\Diactoros\Response\JsonResponse;

abstract class AbstractController
{
    protected Request $request;

    public function __construct()
    {
        $this->request = Request::getInstance();
    }

    protected function json(array $data, int $status = 200): JsonResponse
    {
        return Response::json($data, $status);
    }

    protected function success(array $data, string $message = ''): JsonResponse
    {
        return Response::success($data, $message);
    }

    protected function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $response = [
            'error' => [
                'message' => $message
            ]
        ];

        if (!empty($errors)) {
            $response['error']['errors'] = $errors;
        }

        return new JsonResponse($response, $status);
    }
}
