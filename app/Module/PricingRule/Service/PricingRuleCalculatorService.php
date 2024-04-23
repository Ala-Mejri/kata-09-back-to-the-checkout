<?php

declare(strict_types=1);

namespace App\Module\PricingRule\Service;

use App\Module\Checkout\ValueObject\CheckoutItem;
use App\Module\PricingRule\Rule\PricingRuleInterface;

final class PricingRuleCalculatorService
{
    /**
     * @param PricingRuleInterface[] $itemPricingRules
     */
    public function calculate(CheckoutItem $checkoutItem, array $itemPricingRules): float
    {
        $quantity = $checkoutItem->quantity;
        $total = 0;

        foreach ($itemPricingRules as $itemPricingRule) {
            $total += $itemPricingRule->apply($quantity);
            $quantity -= $itemPricingRule->getAffectedQuantity($quantity);

            if ($quantity === 0) {
                break;
            }
        }

        if ($quantity !== 0) {
            $total += $checkoutItem->item->getPrice() * $quantity;
        }

        return $total;
    }
}
