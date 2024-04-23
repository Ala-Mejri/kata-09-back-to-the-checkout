<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Module\Checkout\Controller\CheckoutController;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

#[CoversClass(CheckoutController::class)]
#[CoversFunction('checkout')]
class CheckoutControllerTest extends TestCase
{
    #[group('Integration')]
    #[dataProvider('provideCorrectCheckoutData')]
    public function testCheckoutShouldReturnCorrectTotal(string $skus, float $expectedResult): void
    {
        // Arrange
        $checkoutController = App::make(CheckoutController::class);
        assert($checkoutController instanceof CheckoutController);

        // Act
        $actualResult = $checkoutController->checkout($skus);
        $formattedActualResult = json_decode($actualResult->getContent(), true);

        // Assert
        $this->assertJsonResponseStructure($formattedActualResult);
        $this->assertEquals(Response::HTTP_OK, $formattedActualResult['code']);
        $this->assertEquals(['Total' => $expectedResult], $formattedActualResult['data']);
    }

    #[group('Integration')]
    #[dataProvider('provideCheckoutNotFoundItemData')]
    public function testCheckoutShouldThrowExceptionWhenItemNotFound(string $skus, array $expectedErrorMessages): void
    {
        // Arrange
        $checkoutController = App::make(CheckoutController::class);
        assert($checkoutController instanceof CheckoutController);

        // Act
        $actualResult = $checkoutController->checkout($skus);
        $formattedActualResult = json_decode($actualResult->getContent(), true);

        // Assert
        $this->assertJsonResponseStructure($formattedActualResult);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $formattedActualResult['code']);
        $this->assertEquals($expectedErrorMessages, $formattedActualResult['errors']);
        $this->assertNull($formattedActualResult['data']);
    }

    public static function provideCorrectCheckoutData(): array
    {
        return [
            'Empty' => [
                '',
                0,
            ],
            'A' => [
                'A',
                50,
            ],
            'AA' => [
                'AA',
                100,
            ],
            'AAA' => [
                'AAA',
                130,
            ],
            'AAAA' => [
                'AAAA',
                180,
            ],
            'AAAAA' => [
                'AAAAA',
                230,
            ],
            'AAAAAA' => [
                'AAAAAA',
                260,
            ],
            'AB' => [
                'AB',
                80
            ],
            'DBCA' => [
                'DBCA',
                115,
            ],
            'AAAB' => [
                'AAAB',
                160,
            ],
            'AAABB' => [
                'AAABB',
                175,
            ],
            'ABBAAD' => [
                'ABBAAD',
                190,
            ],
            'CCC' => [
                'CCC',
                60,
            ],
        ];
    }

    public static function provideCheckoutNotFoundItemData(): array
    {
        return [
            'E' => [
                'E',
                ['Provided item sku E was not found'],
            ],
            'AAAF' => [
                'AAAF',
                ['Provided item sku F was not found'],
            ],
            'TAAA' => [
                'TAAA',
                ['Provided item sku T was not found'],
            ],
            'AAUAA' => [
                'AAUAA',
                ['Provided item sku U was not found'],
            ],
        ];
    }

    private function assertJsonResponseStructure(array $formattedActualResult): void
    {
        $this->isJson();
        $this->assertArrayHasKey('status', $formattedActualResult);
        $this->assertArrayHasKey('code', $formattedActualResult);
        $this->assertArrayHasKey('message', $formattedActualResult);
        $this->assertArrayHasKey('errors', $formattedActualResult);
        $this->assertArrayHasKey('data', $formattedActualResult);
    }
}
