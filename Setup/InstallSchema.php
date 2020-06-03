<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use \Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $tableName = $setup->getTable('cataloginventory_stock_item');
        $connection = $setup->getConnection();

        $connection->addColumn($tableName, 'store_id', Table::TYPE_BIGINT, null, [
            'nullable' => false
        ], 'Store ID');

        $connection->dropIndex($tableName, 'CATINV_STOCK_ITEM_PRD_ID_CAT_PRD_ENTT_ENTT_ID');
        $connection->dropIndex($tableName, 'CATINV_STOCK_ITEM_STOCK_ID_CATINV_STOCK_STOCK_ID');
        $connection->dropIndex($tableName, 'CATALOGINVENTORY_STOCK_ITEM_PRODUCT_ID_STOCK_ID');

        $connection->addForeignKey(
            'CATINV_STOCK_ITEM_PRD_ID_CAT_PRD_ENTT_ENTT_ID',
            $tableName,
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id'
        );

        $connection->addForeignKey(
            'CATINV_STOCK_ITEM_STOCK_ID_CATINV_STOCK_STOCK_ID',
            $tableName,
            'stock_id',
            $setup->getTable('cataloginventory_stock'),
            'stock_id'
        );

        $connection->addIndex($tableName, 'CATALOGINVENTORY_STOCK_ITEM_PRODUCT_ID_STOCK_ID', [
            'product_id',
            'stock_id',
            'store_id',
        ], AdapterInterface::INDEX_TYPE_UNIQUE);

        $setup->endSetup();
    }
}
