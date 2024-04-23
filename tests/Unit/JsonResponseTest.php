<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Shared\Response\JsonResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResponse;
use Symfony\Component\HttpFoundation\Response;

#[CoversClass(JsonResponse::class)]
#[CoversFunction('success')]
#[CoversFunction('notFound')]
#[CoversFunction('validationError')]
#[CoversFunction('error')]
class JsonResponseTest extends TestCase
{
    use ProphecyTrait;

    private JsonResponse $jsonResponse;

    protected function setUp(): void
    {
        $this->jsonResponse = new JsonResponse();
    }

    #[group('Unit')]
    public function testSuccessfulResponse(): void
    {
        // Arrange
        $data = ['Successful response data'];

        $expectedJsonResponse = new BaseJsonResponse([
            'status' => 'success',
            'code' => Response::HTTP_OK,
            'message' => 'OK',
            'errors' => null,
            'data' => $data,
        ], Response::HTTP_OK);

        // Act
        $actualResult = $this->jsonResponse->success($data);

        // Assert
        $this->assertEquals($expectedJsonResponse, $actualResult);
    }

    #[group('Unit')]
    public function testNotFoundResponse(): void
    {
        // Arrange
        $errorMessages = ['404 not found error message'];

        $expectedJsonResponse = new BaseJsonResponse([
            'status' => 'error',
            'code' => Response::HTTP_NOT_FOUND,
            'message' => '404 not found',
            'errors' => $errorMessages,
            'data' => null,
        ], Response::HTTP_NOT_FOUND);

        // Act
        $actualResult = $this->jsonResponse->notFound($errorMessages);

        // Assert
        $this->assertEquals($expectedJsonResponse, $actualResult);
    }

    #[group('Unit')]
    public function testValidationErrorResponse(): void
    {
        // Arrange
        $errorMessages = ['validation error message'];

        $expectedJsonResponse = new BaseJsonResponse([
            'status' => 'error',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'Resource could not be processed because of validation errors',
            'errors' => $errorMessages,
            'data' => null,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        // Act
        $actualResult = $this->jsonResponse->validationError($errorMessages);

        // Assert
        $this->assertEquals($expectedJsonResponse, $actualResult);
    }

    #[group('Unit')]
    public function testErrorResponse(): void
    {
        // Arrange
        $errorMessages = ['500 error message'];

        $expectedJsonResponse = new BaseJsonResponse([
            'status' => 'error',
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => '500 internal server error',
            'errors' => $errorMessages,
            'data' => null,
        ], Response::HTTP_INTERNAL_SERVER_ERROR);

        // Act
        $actualResult = $this->jsonResponse->error($errorMessages);

        // Assert
        $this->assertEquals($expectedJsonResponse, $actualResult);
    }
}
