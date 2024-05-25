<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Module\Checkout\Service\CheckoutItemScanner;
use App\Module\Checkout\Service\CheckoutItemPriceCalculator;
use App\Module\Checkout\ValueObject\CheckoutItem;
use App\Module\Item\Exception\InvalidItemSkuException;
use App\Module\Item\Factory\ItemFactory;
use App\Module\PricingRule\Service\SpringSalePricingRulesBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

#[CoversClass(CheckoutItemScanner::class)]
#[CoversFunction('getTotal')]
class CheckoutItemScannerTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $checkoutItemPriceCalculator;
    private CheckoutItemScanner $checkoutItemScanner;

    protected function setUp(): void
    {
        $this->checkoutItemPriceCalculator = $this->prophesize(CheckoutItemPriceCalculator::class);

        $this->checkoutItemScanner = new CheckoutItemScanner($this->checkoutItemPriceCalculator->reveal());
    }

    /**
     * @throws InvalidItemSkuException
     */
    #[group('Unit')]
    #[dataProvider('provideCheckoutData')]
    public function testCheckoutShouldReturnCorrectTotal(array $items, array $checkoutItems, float $expectedResult): void
    {
        // Arrange
        $pricingRules = (new SpringSalePricingRulesBuilder())->build();
        $this->checkoutItemScanner->setPricingRules($pricingRules);

        foreach ($items as $item) {
            $this->checkoutItemScanner->scan($item);
        }

        foreach ($checkoutItems as $checkoutItem) {
            $this->checkoutItemPriceCalculator->calculate($checkoutItem[0], $pricingRules)
                ->shouldBeCalledOnce()
                ->willReturn($checkoutItem[1]);
        }

        // Act
        $actualResult = $this->checkoutItemScanner->getTotal();

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
                [
                    [
                        new CheckoutItem(ItemFactory::createA(), 1),
                        50,
                    ],
                ],
                50,
            ],
            '2A' => [
                [
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                ],
                [
                    [
                        new CheckoutItem(ItemFactory::createA(), 2),
                        100,
                    ],
                ],
                100,
            ],
            '3A' => [
                [
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                    ItemFactory::createA(),
                ],
                [
                    [
                        new CheckoutItem(ItemFactory::createA(), 3),
                        130,
                    ],
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
                [
                    [
                        new CheckoutItem(ItemFactory::createA(), 4),
                        180,
                    ],
                ],
                180,
            ],
            '2B' => [
                [
                    ItemFactory::createB(),
                    ItemFactory::createB(),
                ],
                [
                    [
                        new CheckoutItem(ItemFactory::createB(), 2),
                        45,
                    ],
                ],
                45,
            ],
            '3B' => [
                [
                    ItemFactory::createB(),
                    ItemFactory::createB(),
                    ItemFactory::createB(),
                ],
                [
                    [
                        new CheckoutItem(ItemFactory::createB(), 3),
                        75,
                    ],
                ],
                75,
            ],
            '2C' => [
                [
                    ItemFactory::createC(),
                    ItemFactory::createC(),
                ],
                [
                    [
                        new CheckoutItem(ItemFactory::createC(), 2),
                        40,
                    ],
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
                [
                    [
                        new CheckoutItem(ItemFactory::createA(), 3),
                        130,
                    ],
                    [
                        new CheckoutItem(ItemFactory::createC(), 2),
                        40,
                    ],
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
                [
                    [
                        new CheckoutItem(ItemFactory::createA(), 3),
                        130,
                    ],
                    [
                        new CheckoutItem(ItemFactory::createC(), 2),
                        40,
                    ],
                    [
                        new CheckoutItem(ItemFactory::createB(), 2),
                        45,
                    ],
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
                [
                    [
                        new CheckoutItem(ItemFactory::createA(), 3),
                        130,
                    ],
                    [
                        new CheckoutItem(ItemFactory::createC(), 2),
                        40,
                    ],
                    [
                        new CheckoutItem(ItemFactory::createB(), 2),
                        45,
                    ],
                    [
                        new CheckoutItem(ItemFactory::createD(), 4),
                        60,
                    ],
                ],
                275,
            ],
        ];
    }
}
