<?php

declare(strict_types=1);

namespace App\Module\PricingRule\Collection;

use App\Module\Item\Model\Item;
use App\Module\PricingRule\Rule\PricingRuleInterface;

final class PricingRuleCollection
{
    /** @var PricingRuleInterface[][] */
    public array $pricingRules = [];

    public function add(Item $item, PricingRuleInterface $pricingRule): self
    {
        $this->pricingRules[$item->getSku()][] = $pricingRule;

        return $this;
    }

    /**
     * @return PricingRuleInterface[]
     */
    public function get(Item $item): array
    {
        return $this->pricingRules[$item->getSku()] ?? [];
    }
}
