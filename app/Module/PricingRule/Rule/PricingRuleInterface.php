<?php

declare(strict_types = 1);

namespace App\Module\PricingRule\Rule;

interface PricingRuleInterface
{
    public function apply(int $quantity) : float;

    public function getAffectedQuantity(int $quantity): int;
}
