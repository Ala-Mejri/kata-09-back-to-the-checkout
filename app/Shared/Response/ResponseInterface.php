<?php

declare(strict_types=1);

namespace App\Shared\Response;

use Symfony\Component\HttpFoundation\Response;

interface ResponseInterface
{
    public function success(array $data): Response;

    public function notFound(array $errorMessages): Response;

    public function validationError(array $errorMessages): Response;

    public function error(array $errorMessages): Response;
}
