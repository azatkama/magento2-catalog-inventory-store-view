<?php

declare(strict_types=1);

namespace Azatkama\CatalogInventoryStoreView;

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
        $installer = $setup;

        $installer->startSetup();
        $tableName = $installer->getTable('cataloginventory_stock_item');

        $table = $installer->getConnection()
            ->addColumn($tableName, 'store_id', Table::TYPE_BIGINT, null, [
                'nullable' => false
            ], 'Store ID');

        $table->dropIndex($tableName, 'CATALOGINVENTORY_STOCK_ITEM_PRODUCT_ID_STOCK_ID');
        $table->addIndex($tableName, 'CATALOGINVENTORY_STOCK_ITEM_PRODUCT_ID_STOCK_ID', [
            'product_id',
            'stock_id',
            'store_id',
        ]);

        $installer->endSetup();
    }
}
