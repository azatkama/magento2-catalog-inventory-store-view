<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Api\Data;

use Azatkama\CatalogInventoryStoreView\Api\StockStoreItemCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface StockStoreItemCollectionInterface extends SearchResultsInterface
{
    /**
     * @return StockStoreItemInterface[]
     */
    public function getItems();

    /**
     * @param StockStoreItemInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items);

    /**
     * @return StockStoreItemCriteriaInterface
     */
    public function getSearchCriteria();
}
