<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Model;

use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemCollectionInterface;
use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemCollectionInterfaceFactory;
use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemInterface;
use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemInterfaceFactory;
use Azatkama\CatalogInventoryStoreView\Api\StockStoreItemCriteriaInterface;
use Azatkama\CatalogInventoryStoreView\Api\StockStoreItemCriteriaInterfaceFactory;
use Azatkama\CatalogInventoryStoreView\Api\StockStoreItemRepositoryInterface;
use Azatkama\CatalogInventoryStoreView\Model\ResourceModel\StockStoreItem as ResourceStockStoreItem;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\Stock\Item;
use Magento\Framework\DB\QueryBuilderFactory;

class StockStoreItemRepository implements StockStoreItemRepositoryInterface
{
    /**
     * @var ResourceStockStoreItem
     */
    protected $resource;

    /**
     * @var QueryBuilderFactory
     */
    protected $queryBuilderFactory;

    /**
     * @var StockStoreItemInterfaceFactory
     */
    protected $stockStoreItemFactory;

    /**
     * @var StockStoreItemCriteriaInterfaceFactory
     */
    protected $stockStoreItemCriteriaInterfaceFactory;

    /**
     * @var StockStoreItemCollectionInterfaceFactory
     */
    protected $stockStoreItemCollectionInterfaceFactory;

    /**
     * @param ResourceStockStoreItem $resource
     * @param QueryBuilderFactory $queryBuilderFactory
     * @param StockStoreItemCriteriaInterfaceFactory $stockStoreItemCriteriaInterfaceFactory
     * @param StockStoreItemCollectionInterfaceFactory $stockStoreItemCollectionInterfaceFactory
     */
    public function __construct(
        ResourceStockStoreItem $resource,
        QueryBuilderFactory $queryBuilderFactory,
        StockStoreItemInterfaceFactory $stockStoreItemFactory,
        StockStoreItemCriteriaInterfaceFactory $stockStoreItemCriteriaInterfaceFactory,
        StockStoreItemCollectionInterfaceFactory $stockStoreItemCollectionInterfaceFactory
    ) {
        $this->resource = $resource;
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->stockStoreItemFactory = $stockStoreItemFactory;
        $this->stockStoreItemCriteriaInterfaceFactory = $stockStoreItemCriteriaInterfaceFactory;
        $this->stockStoreItemCollectionInterfaceFactory = $stockStoreItemCollectionInterfaceFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(StockStoreItemInterface $stockStoreItem): StockStoreItemInterface
    {
        try {
            $stockStoreItem->setStoreId($stockStoreItem->getStoreId());
            $this->resource->save($stockStoreItem);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('The stock store item was unable to be saved. Please try again.'), $exception);
        }

        return $stockStoreItem;
    }

    /**
     * @inheritdoc
     */
    public function get(StockItemInterface $stockItem): StockStoreItemInterface
    {
        $criteria = $this->stockStoreItemCriteriaInterfaceFactory->create();
        /** @var Item $stockItem */
        $criteria->addFilter('item_id', 'item_id', $stockItem->getItemId());
        $criteria->addFilter('store_id', 'store_id', $stockItem->getStoreId());

        $collection = $this->getList($criteria);
        $stockStoreItem = current($collection->getItems());

        if (!$stockStoreItem) {
            $stockStoreItem = $this->stockStoreItemFactory->create();
        }

        return $stockStoreItem;
    }

    /**
     * @inheritdoc
     */
    public function getList(StockStoreItemCriteriaInterface $criteria): StockStoreItemCollectionInterface
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->setCriteria($criteria);
        $queryBuilder->setResource($this->resource);
        $query = $queryBuilder->create();

        $collection = $this->stockStoreItemCollectionInterfaceFactory->create(['query' => $query]);

        return $collection;
    }

    /**
     * @inheritdoc
     */
    public function delete(StockStoreItemInterface $stockItem): bool
    {
        try {
            $this->resource->delete($stockItem);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'The stock store item with the "%1" ID wasn\'t found. Verify the ID and try again.',
                    $stockItem->getItemId()
                ),
                $exception
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById(int $id): bool
    {
        try {
            $stockStoreItem = $this->get($id);

            $this->delete($stockStoreItem);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('The stock store item with the "%1" ID wasn\'t found. Verify the ID and try again.', $id),
                $exception
            );
        }

        return true;
    }
}
