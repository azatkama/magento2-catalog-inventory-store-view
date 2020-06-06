<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Model;

use Azatkama\CatalogInventoryStoreView\Api\Data\StockStoreItemInterface;
use Azatkama\CatalogInventoryStoreView\Model\ResourceModel\StockStoreItem as ResourceStockStoreItem;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

class StockStoreItem extends AbstractExtensibleModel implements StockStoreItemInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = ResourceStockStoreItem::ENTITY;

    /**
     * @var string
     */
    protected $_eventObject = 'stock_store_item';

    /**
     * Store model manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var StockConfigurationInterface
     */
    protected $stockConfiguration;

    /**
     * Store id
     *
     * @var int|null
     */
    protected $storeId;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param StockConfigurationInterface $stockConfiguration
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        StoreManagerInterface $storeManager,
        StockConfigurationInterface $stockConfiguration,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );

        $this->storeManager = $storeManager;
        $this->stockConfiguration = $stockConfiguration;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceStockStoreItem::class);
    }

    /**
     * @inheritDoc
     */
    public function getItemId(): ?int
    {
        $itemId = $this->_getData(static::ITEM_ID);

        return $itemId !== null ? (int)$itemId : null;
    }

    /**
     * @inheritDoc
     */
    public function setItemId(int $itemId): void
    {
        $this->setData(static::ITEM_ID, $itemId);
    }

    /**
     * @inheritDoc
     */
    public function getStoreId(): int
    {
        $storeId = $this->getData(static::STORE_ID);

        if ($storeId === null) {
            $storeId = $this->storeManager->getStore()->getId();
        }

        return (int) $storeId;
    }

    /**
     * @inheritDoc
     */
    public function setStoreId(int $storeId): void
    {
        $this->setData(static::STORE_ID, $storeId);
    }

    /**
     * @inheritDoc
     */
    public function getMinQty(): float
    {
        if ($this->isUseConfigMinQty()) {
            $minQty = $this->stockConfiguration->getMinQty($this->getStoreId());
        } else {
            $minQty = (float)$this->getData(static::MIN_QTY);
        }

        return $minQty;
    }

    /**
     * @inheritDoc
     */
    public function setMinqty(float $minQty): void
    {
        $this->setData(static::MIN_QTY, $minQty);
    }

    /**
     * @inheritDoc
     */
    public function isUseConfigMinQty(): bool
    {
        return (bool)$this->getData(static::USE_CONFIG_MIN_QTY);
    }

    /**
     * @inheritDoc
     */
    public function setUseConfigMinQty(bool $useConfigMinQty): void
    {
        $this->setData(static::USE_CONFIG_MIN_QTY, $useConfigMinQty);
    }
}
