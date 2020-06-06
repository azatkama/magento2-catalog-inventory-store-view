<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Api;

use Magento\Framework\Api\CriteriaInterface;

interface StockStoreItemCriteriaInterface extends CriteriaInterface
{
    /**
     * @param StockStoreItemCriteriaInterface $criteria
     *
     * @return bool
     */
    public function addCriteria(StockStoreItemCriteriaInterface $criteria);
}
