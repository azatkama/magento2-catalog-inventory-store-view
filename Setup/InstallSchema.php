<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

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
        $tableName = $setup->getTable('cataloginventory_stock_store_item');
        $connection = $setup->getConnection();

        $table = $connection
            ->newTable($tableName)
            ->addColumn('id', Table::TYPE_BIGINT, null, [
                'nullable' => false,
                'primary' => true,
                'unsigned' => true,
                'identity' => true,
            ], 'ID')
            ->addColumn('item_id', Table::TYPE_INTEGER, 10, [
                'nullable' => false,
                'unsigned' => true,
            ], 'Item ID')
            ->addColumn('store_id', Table::TYPE_BIGINT, null, [
                'nullable' => false,
                'unsigned' => true,
                'default' => 0,
            ], 'Store ID')
            ->addColumn('min_qty', Table::TYPE_DECIMAL, '12,4', [
                'nullable' => false,
                'default' => 0,
            ], 'Min Qty')
            ->addColumn('use_config_min_qty', Table::TYPE_SMALLINT, null, [
                'nullable' => false,
                'default' => 1,
            ], 'Use Config Min Qty')
            ->addForeignKey(
                'cataloginventory_stock_store_item_item_id_fk',
                'item_id',
                $setup->getTable('cataloginventory_stock_item'),
                'item_id',
                Table::ACTION_CASCADE,
            )
            ->addIndex(
                'cataloginventory_stock_store_item_item_id_store_id',
                ['item_id', 'store_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            );

        $connection->createTable($table);

        $setup->endSetup();
    }
}
