<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Plugin;

use Azatkama\CatalogInventoryStoreView\Api\StockStoreItemRepositoryInterface;
use Azatkama\CatalogInventoryStoreView\Model\StockStoreItem;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Ui\DataProvider\Product\Form\Modifier\AdvancedInventory;

class AdvancedInventoryPlugin
{
    /**
     * @var StockStoreItemRepositoryInterface
     */
    protected $stockStoreItemRepository;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @param StockStoreItemRepositoryInterface $stockStoreItemRepository
     * @param LocatorInterface $locator
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        StockStoreItemRepositoryInterface $stockStoreItemRepository,
        LocatorInterface $locator,
        StockRegistryInterface $stockRegistry
    ) {
        $this->stockStoreItemRepository = $stockStoreItemRepository;
        $this->locator = $locator;
        $this->stockRegistry = $stockRegistry;
    }

    /**
     * @param AdvancedInventory $advancedInventory,
     * @param callable $proceed
     * @param array $data
     */
    public function aroundModifyData(
        AdvancedInventory $advancedInventory,
        callable $proceed,
        array $data
    ) {
        $data = $proceed($data);

        $model = $this->locator->getProduct();
        $modelId = $model->getId();

        /** @var StockItemInterface $stockItem */
        $stockItem = $this->stockRegistry->getStockItem(
            $modelId,
            $model->getStore()->getWebsiteId()
        );

        $stockStoreItem = $this->stockStoreItemRepository->get($stockItem);

        if (!$stockStoreItem->getItemId()) {
            return $data;
        }

        $stockData = $data[$modelId][AdvancedInventory::DATA_SOURCE_DEFAULT][AdvancedInventory::STOCK_DATA_FIELDS];

        $stockData[StockStoreItem::MIN_QTY] = $stockStoreItem->getData(StockStoreItem::MIN_QTY);
        $stockData[StockStoreItem::USE_CONFIG_MIN_QTY] = $stockStoreItem->getData(StockStoreItem::USE_CONFIG_MIN_QTY);

        $data[$modelId][AdvancedInventory::DATA_SOURCE_DEFAULT][AdvancedInventory::STOCK_DATA_FIELDS] = $stockData;

        return $data;
    }
}
