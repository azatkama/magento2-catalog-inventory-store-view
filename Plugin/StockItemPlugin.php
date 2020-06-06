<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Plugin;

use Azatkama\CatalogInventoryStoreView\Api\StockStoreItemRepositoryInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Model\Stock\Item;

class StockItemPlugin
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
     * @param Item $stockItem,
     * @param callable $proceed
     */
    public function aroundGetMinQty(
        Item $stockItem,
        callable $proceed
    ) {
        $stockStoreItem = $this->stockStoreItemRepository->get($stockItem);

        if (!$stockStoreItem->getItemId()) {
            return $proceed();
        }

        return $stockStoreItem->getMinQty();
    }

    /**
     * @param Item $stockItem,
     * @param callable $proceed
     */
    public function aroundGetUseConfigMinQty(
        Item $stockItem,
        callable $proceed
    ) {
        $stockStoreItem = $this->stockStoreItemRepository->get($stockItem);

        if (!$stockStoreItem->getItemId()) {
            return $proceed();
        }

        return $stockStoreItem->isUseConfigMinQty();
    }
}
