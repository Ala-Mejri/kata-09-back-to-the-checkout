<?php

declare(strict_types=1);

namespace App\Module\Checkout\Service;

use App\Module\Checkout\ValueObject\CheckoutItem;
use App\Module\Item\Model\Item;
use App\Module\PricingRule\Collection\PricingRuleCollection;

final class CheckoutItemScanner
{
    /** @var CheckoutItem[] */
    private array $checkoutItems = [];
    private PricingRuleCollection $pricingRuleCollection;

    public function __construct(private readonly CheckoutItemPriceCalculator $checkoutItemPriceCalculator)
    {
    }

    public function setPricingRules(PricingRuleCollection $pricingRuleCollection): self
    {
        $this->pricingRuleCollection = $pricingRuleCollection;

        return $this;
    }

    public function scan(Item $item): self
    {
        $this->checkoutItems[$item->getSku()] = $this->getCheckoutItem($item)->incrementQuantity();

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->checkoutItems as $checkoutItem) {
            $total += $this->checkoutItemPriceCalculator->calculate($checkoutItem, $this->pricingRuleCollection);
        }

        return $total;
    }

    private function getCheckoutItem(Item $item): CheckoutItem
    {
        return $this->checkoutItems[$item->getSku()] ?? new CheckoutItem($item, 0);
    }
}
