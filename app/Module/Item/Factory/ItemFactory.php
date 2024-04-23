<?php

declare(strict_types=1);

namespace App\Module\Item\Factory;

use App\Module\Item\Enum\ItemSkuType;
use App\Module\Item\Exception\InvalidItemSkuException;
use App\Module\Item\Model\Item;

final class ItemFactory
{
    /**
     * @throws InvalidItemSkuException
     */
    public static function createA(): Item
    {
        return self::create(ItemSkuType::A->value);
    }

    /**
     * @throws InvalidItemSkuException
     */
    public static function createB(): Item
    {
        return self::create(ItemSkuType::B->value);
    }

    /**
     * @throws InvalidItemSkuException
     */
    public static function createC(): Item
    {
        return self::create(ItemSkuType::C->value);
    }

    /**
     * @throws InvalidItemSkuException
     */
    public static function createD(): Item
    {
        return self::create(ItemSkuType::D->value);
    }

    /**
     * @throws InvalidItemSkuException
     */
    public static function create(string $sku): Item
    {
        $price = self::getPrice($sku);

        return new Item($sku, $price);
    }

    /**
     * @throws InvalidItemSkuException
     */
    private static function getPrice(string $sku): float
    {
        $itemSkuType = ItemSkuType::tryFrom($sku);

        if ($itemSkuType === null) {
            throw new InvalidItemSkuException(sprintf('Provided item sku %s was not found', $sku));
        }

        return $itemSkuType->getPrice();
    }
}
