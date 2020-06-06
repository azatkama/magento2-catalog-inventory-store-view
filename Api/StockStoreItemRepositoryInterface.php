<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Api;

use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemCollectionInterface;
use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;

interface StockStoreItemRepositoryInterface
{
    /**
     * @param StockStoreItemInterface $stockStoreItem
     *
     * @return StockStoreItemInterface
     */
    public function save(StockStoreItemInterface $stockStoreItem): StockStoreItemInterface;

    /**
     * @param StockItemInterface $stockItem
     *
     * @return StockStoreItemInterface
     */
    public function get(StockItemInterface $stockItem): StockStoreItemInterface;

    /**
     * @param StockStoreItemCriteriaInterface $criteria
     *
     * @return StockStoreItemCollectionInterface
     */
    public function getList(StockStoreItemCriteriaInterface $criteria): StockStoreItemCollectionInterface;

    /**
     * @param StockStoreItemInterface $stockStoreItem
     *
     * @return bool
     */
    public function delete(StockStoreItemInterface $stockStoreItem): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
