<?php

declare(strict_types=1);

namespace App\Module\PricingRule\Builder;

use App\Module\Item\Exception\InvalidItemSkuException;
use App\Module\PricingRule\Collection\PricingRuleCollection;

interface PricingRulesBuilderInterface
{
    /**
     * @throws InvalidItemSkuException
     */
    public function build(): PricingRuleCollection;
}
