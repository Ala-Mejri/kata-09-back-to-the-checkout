<?php

declare(strict_types=1);

namespace App\Shared\Response;

use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JsonResponse implements ResponseInterface
{
    public function success(array $data): BaseJsonResponse
    {
        return new BaseJsonResponse([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'message' => 'OK',
            'errors' => null,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function notFound(array $errorMessages): BaseJsonResponse
    {
        return new BaseJsonResponse([
            'status' => 'error',
            'code' => Response::HTTP_NOT_FOUND,
            'message' => '404 not found',
            'errors' => $errorMessages,
            'data' => null,
        ], Response::HTTP_NOT_FOUND);
    }

    public function validationError(array $errorMessages): BaseJsonResponse
    {
        return new BaseJsonResponse([
            'status' => 'error',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'Resource could not be processed because of validation errors',
            'errors' => $errorMessages,
            'data' => null,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function error(array $errorMessages): BaseJsonResponse
    {
        return new BaseJsonResponse([
            'status' => 'error',
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => '500 internal server error',
            'errors' => $errorMessages,
            'data' => null,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
