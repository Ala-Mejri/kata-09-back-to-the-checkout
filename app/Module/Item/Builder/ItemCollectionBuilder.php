<?php

declare(strict_types=1);

namespace App\Module\Item\Builder;

use App\Module\Item\Collection\ItemCollection;
use App\Module\Item\Exception\InvalidItemSkuException;
use App\Module\Item\Factory\ItemFactory;

final class ItemCollectionBuilder
{
    /**
     * @throws InvalidItemSkuException
     */
    public function build(string $itemSkus): ItemCollection
    {
        $itemSkus = str_split($itemSkus);
        $itemCollection = new ItemCollection();

        foreach ($itemSkus as $itemSku) {
            $item = ItemFactory::create($itemSku);
            $itemCollection->append($item);
        }

        return $itemCollection;
    }
}
