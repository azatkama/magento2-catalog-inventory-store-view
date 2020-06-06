<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Model\ResourceModel;

use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemCollectionInterface;
use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemInterface;
use Magento\Framework\Data\AbstractSearchResult;

class Collection extends AbstractSearchResult implements StockStoreItemCollectionInterface
{
    /**
     * @inheritdoc
     */
    protected function init()
    {
        $this->setDataInterfaceName(StockStoreItemInterface::class);
    }
}
