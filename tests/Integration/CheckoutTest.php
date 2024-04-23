<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Module\Checkout\Service\Checkout;
use App\Module\Item\Exception\InvalidItemSkuException;
use App\Module\Item\Factory\ItemFactory;
use App\Module\PricingRule\Builder\SpringSalePricingRulesBuilder;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[CoversClass(Checkout::class)]
#[CoversFunction('getTotal')]
class CheckoutTest extends TestCase
{
    /**
     * @throws InvalidItemSkuException
     */
    #[group('Integration')]
    #[dataProvider('provideCheckoutData')]
    public function testCheckoutShouldReturnCorrectTotal(array $items, float $expectedResult): void
    {
        // Arrange
        $checkout = App::make(Checkout::class);
        assert($checkout instanceof Checkout);

        $springSalePricingRulesBuilder = App::make(SpringSalePricingRulesBuilder::class);
        assert($springSalePricingRulesBuilder instanceof SpringSalePricingRulesBuilder);

        $pricingRules = $springSalePricingRulesBuilder->build();
        $checkout->setPricingRules($pricingRules);

        foreach ($items as $item) {
            $checkout->scan($item);
        }

        // Act
        $actualResult = $checkout->getTotal();

        // Assert
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @throws InvalidItemSkuException
     */
    public static function provideCheckoutData(): array
    {
        return [
            '1A' => [
                [
                    ItemFactory::createA(),
                ],
                50,
            ],
            '2A' => [
                [
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                ],
                100,
            ],
            '3A' => [
                [
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                ],
                130,
            ],
            '4A' => [
                [
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                ],
                180,
            ],
            '2B' => [
                [
                    ItemFactory::createB(),
                    ItemFactory::createB(),
                ],
                45,
            ],
            '3B' => [
                [
                    ItemFactory::createB(),
                    ItemFactory::createB(),
                    ItemFactory::createB(),
                ],
                75,
            ],
            '2C' => [
                [
                    ItemFactory::createC(),
                    ItemFactory::createC(),
                ],
                40,
            ],
            '3A-2C' => [
                [
                    ItemFactory::createA(),
                    ItemFactory::createC(),
                    ItemFactory::createA(),
                    ItemFactory::createC(),
                    ItemFactory::createA(),
                ],
                170,
            ],
            '3A-2C-2B' => [
                [
                    ItemFactory::createB(),
                    ItemFactory::createA(),
                    ItemFactory::createC(),
                    ItemFactory::createA(),
                    ItemFactory::createC(),
                    ItemFactory::createA(),
                    ItemFactory::createB(),
                ],
                215,
            ],
            '3A-2C-2B-4D' => [
                [
                    ItemFactory::createD(),
                    ItemFactory::createB(),
                    ItemFactory::createA(),
                    ItemFactory::createC(),
                    ItemFactory::createD(),
                    ItemFactory::createD(),
                    ItemFactory::createA(),
                    ItemFactory::createC(),
                    ItemFactory::createA(),
                    ItemFactory::createB(),
                    ItemFactory::createD(),
                ],
                275,
            ],
        ];
    }
}
