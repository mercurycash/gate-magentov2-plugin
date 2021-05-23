<?php

declare(strict_types=1);

namespace Mercury\Payment\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tables = [
            'quote_payment',
            'sales_order_payment'
        ];

        foreach ($tables as $table) {
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable($table),
                    'mercury_transaction',
                    [
                        'type' => Table::TYPE_TEXT,
                        'length' => 255,
                        'default' => '',
                        'nullable' => true,
                        'comment' => 'Blockchain Transaction',
                    ]
                );
        }

        $setup->endSetup();
    }
}
