<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Chetu\Rule\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Upgrade the SalesRule module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $installer = $setup;

            $installer->startSetup();
        
            $installer->getConnection()->addColumn(
                $installer->getTable('salesrule'),
                'display_coupons',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 11,
                    'nullable' => false,
                    'default' => '0',
                    'comment' => '0 - Let product decide, 1 - Yes, 2 - No'
                ]
            );
        
            $installer->endSetup();

        }
        
        $setup->endSetup();

        
    }
}
