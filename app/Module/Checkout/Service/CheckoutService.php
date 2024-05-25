<?php

declare(strict_types=1);

namespace App\Module\Checkout\Service;

use App\Module\Item\Exception\InvalidItemSkuException;
use App\Module\Item\Service\ItemCollectionBuilder;
use App\Module\PricingRule\Collection\PricingRuleCollection;

final readonly class CheckoutService
{
    public function __construct(
        private CheckoutItemScanner   $checkoutItemScanner,
        private ItemCollectionBuilder $itemCollectionBuilder,
    )
    {
    }

    /**
     * @throws InvalidItemSkuException
     */
    public function getTotal(string $skus, PricingRuleCollection $pricingRules): float
    {
        $this->checkoutItemScanner->setPricingRules($pricingRules);
        $checkoutItemCollection = $this->itemCollectionBuilder->build($skus);

        foreach ($checkoutItemCollection as $item) {
            $this->checkoutItemScanner->scan($item);
        }

        return $this->checkoutItemScanner->getTotal();
    }
}
