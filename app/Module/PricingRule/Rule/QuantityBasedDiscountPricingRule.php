<?php

declare(strict_types=1);

namespace App\Module\PricingRule\Rule;

final readonly class QuantityBasedDiscountPricingRule implements PricingRuleInterface
{
    public function __construct(private int $quantity, private float $price)
    {
    }

    public function apply(int $quantity): float
    {
        return $this->canBeApplied($quantity)
            ? intdiv($quantity, $this->quantity) * $this->price
            : 0;
    }

    public function getAffectedQuantity(int $quantity): int
    {
        return $this->canBeApplied($quantity)
            ? intdiv($quantity, $this->quantity) * $this->quantity
            : 0;
    }

    private function canBeApplied(int $quantity): bool
    {
        return $quantity >= $this->quantity;
    }
}
