<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Model\ResourceModel;

use Azatkama\CatalogInventoryStoreView\Api\StockStoreItemCriteriaInterface;
use Magento\Framework\Data\AbstractCriteria;

class StockStoreItemCriteria extends AbstractCriteria implements StockStoreItemCriteriaInterface
{
    /**
     * @param string $mapper
     */
    public function __construct($mapper = '')
    {
        $this->mapperInterfaceName = $mapper ?: StockStoreItemCriteriaMapper::class;
        $this->data['initial_condition'] = [true];
    }

    /**
     * @inheritDoc
     */
    public function addCriteria(StockStoreItemCriteriaInterface $criteria)
    {
        $this->data[self::PART_CRITERIA_LIST]['list'][] = $criteria;

        return true;
    }
}
