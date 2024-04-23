<?php

declare(strict_types=1);

namespace App\Module\Checkout\Service;

use App\Module\Checkout\ValueObject\CheckoutItem;
use App\Module\PricingRule\Collection\PricingRuleCollection;
use App\Module\PricingRule\Service\PricingRuleCalculatorService;

class CheckoutItemPriceCalculator
{
    public function __construct(private PricingRuleCalculatorService $pricingRuleCalculatorService)
    {
    }

    public function calculate(CheckoutItem $checkoutItem, PricingRuleCollection $pricingRules): float
    {
        $itemPricingRules = $pricingRules->get($checkoutItem->item);

        if ($itemPricingRules === []) {
            return $this->calculatePriceWithoutRules($checkoutItem);
        }

        return $this->pricingRuleCalculatorService->calculate($checkoutItem, $itemPricingRules);
    }

    private function calculatePriceWithoutRules(CheckoutItem $checkoutItem): float
    {
        return $checkoutItem->quantity > 0
            ? $checkoutItem->quantity * $checkoutItem->item->getPrice()
            : 0;
    }
}
