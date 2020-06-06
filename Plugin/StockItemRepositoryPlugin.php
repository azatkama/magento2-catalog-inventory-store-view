<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Plugin;

use Azatkama\CatalogInventoryStoreView\Api\StockStoreItemRepositoryInterface;
use Azatkama\CatalogInventoryStoreView\Model\StockStoreItem;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Api\StockItemRepositoryInterface;
use Magento\CatalogInventory\Model\Stock\Item;

class StockItemRepositoryPlugin
{
    /**
     * @var StockStoreItemRepositoryInterface
     */
    protected $stockStoreItemRepository;

    /**
     * @var StockConfigurationInterface
     */
    protected $stockConfiguration;

    /**
     * @param StockStoreItemRepositoryInterface $stockStoreItemRepository
     * @param StockConfigurationInterface $stockConfiguration
     */
    public function __construct(
        StockStoreItemRepositoryInterface $stockStoreItemRepository,
        StockConfigurationInterface $stockConfiguration
    ) {
        $this->stockStoreItemRepository = $stockStoreItemRepository;
        $this->stockConfiguration = $stockConfiguration;
    }

    /**
     * @param StockItemRepositoryInterface $stockItemRepository
     * @param callable $proceed
     * @param StockItemInterface $stockItem
     */
    public function aroundSave(
        StockItemRepositoryInterface $stockItemRepository,
        callable $proceed,
        StockItemInterface $stockItem
    ) {
        /** @var array $data */
        $data = $stockItem->getData();

        $minQty = (float)$data[StockStoreItem::MIN_QTY];
        $useConfigMinQty = (bool)$data[StockStoreItem::USE_CONFIG_MIN_QTY];
        $data[StockStoreItem::MIN_QTY] = $this->stockConfiguration->getMinQty($stockItem->getStoreId());
        $data[StockStoreItem::USE_CONFIG_MIN_QTY] = true;

        $stockItem->setData($data);

        /** @var Item $stockItem */
        $stockItem = $proceed($stockItem);

        $stockStoreItem = $this->stockStoreItemRepository->get($stockItem);
        $stockStoreItem->setItemId((int)$stockItem->getItemId());
        $stockStoreItem->setMinqty($minQty);
        $stockStoreItem->setUseConfigMinQty($useConfigMinQty);
        $this->stockStoreItemRepository->save($stockStoreItem);

        return $stockItem;
    }
}
