<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Model\ResourceModel;

use Magento\Framework\DB\GenericMapper;

class StockStoreItemCriteriaMapper extends GenericMapper
{
    /**
     * @inheritdoc
     */
    protected function init()
    {
        $this->initResource(StockStoreItem::class);
    }
}
