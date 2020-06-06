<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Stock item resource model
 */
class StockStoreItem extends AbstractDb
{
    const ENTITY = 'cataloginventory_stock_store_item';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(static::ENTITY, 'id');
    }
}
