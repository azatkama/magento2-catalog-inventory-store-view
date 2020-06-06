<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Api\Data;

interface StockStoreItemInterface
{
    public const ITEM_ID = 'item_id';
    public const STORE_ID = 'store_id';
    public const MIN_QTY = 'min_qty';
    public const USE_CONFIG_MIN_QTY = 'use_config_min_qty';

    /**
     * @return int|null
     */
    public function getItemId(): ?int;

    /**
     * @param int $itemId
     */
    public function setItemId(int $itemId): void;

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @param int $storeId
     */
    public function setStoreId(int $storeId): void;

    /**
     * @return float
     */
    public function getMinQty(): float;

    /**
     * @param float $minQty
     */
    public function setMinqty(float $minQty): void;

    /**
     * @return bool
     */
    public function isUseConfigMinQty(): bool;

    /**
     * @param bool $useConfigMinQty
     */
    public function setUseConfigMinQty(bool $useConfigMinQty): void;
}
