<?php

declare(strict_types=1);

namespace App\Module\PricingRule\Service;

use App\Module\Item\Factory\ItemFactory;
use App\Module\PricingRule\Collection\PricingRuleCollection;
use App\Module\PricingRule\Rule\QuantityBasedDiscountPricingRule;

final class SpringSalePricingRulesBuilder implements PricingRulesBuilderInterface
{
    /**
     * @inheritDoc
     */
    public function build(): PricingRuleCollection
    {
        $itemA = ItemFactory::createA();
        $itemB = ItemFactory::createB();

        $pricingRuleCollection = new PricingRuleCollection();

        return $pricingRuleCollection
            ->add($itemA, new QuantityBasedDiscountPricingRule(3, 130))
            ->add($itemB, new QuantityBasedDiscountPricingRule(2, 45));
    }
}
