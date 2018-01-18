<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_Salesforce extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_Salesforce
 * @author   ThaoPV
 */
namespace Magenest\Salesforce\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('magenest_salesforce_map')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
             'identity' => true,
             'nullable' => false,
             'primary'  => true,
            ],
            'Map ID'
        )->addColumn(
            'salesforce',
            Table::TYPE_TEXT,
            50,
            ['nullable' => false],
            'Salesforce Field'
        )->addColumn(
            'magento',
            Table::TYPE_TEXT,
            50,
            ['nullable' => false],
            'Magento Field'
        )->addColumn(
            'type',
            Table::TYPE_TEXT,
            null,
            [],
            'Type'
        )->addColumn(
            'description',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Description'
        )->addColumn(
            'status',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Active Status'
        )->setComment(
            'Mapping Table'
        );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('magenest_salesforce_field')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
             'identity' => true,
             'nullable' => false,
             'primary'  => true,
            ],
            'Field ID'
        )->addColumn(
            'type',
            Table::TYPE_TEXT,
            30,
            ['nullable' => false],
            'Type'
        )->addColumn(
            'salesforce',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Salesforce Field'
        )->addColumn(
            'magento',
            Table::TYPE_TEXT,
            '30',
            ['nullable' => false],
            'Magento Field'
        );

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('magenest_salesforce_report')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
             'identity' => true,
             'nullable' => false,
             'primary'  => true,
            ],
            'Field ID'
        )->addColumn(
            'record_id',
            Table::TYPE_TEXT,
            50,
            ['nullable' => true],
            'Record Id in Salesforce'
        )->addColumn(
            'id_magento',
            Table::TYPE_INTEGER,
            12,
            ['nullable' => true],
            'Id in Magento'
        )->addColumn(
            'action',
            Table::TYPE_TEXT,
            20,
            ['nullable' => true],
            'Action'
        )->addColumn(
            'salesforce_table',
            Table::TYPE_TEXT,
            20,
            ['nullable' => true],
            'Table of Salesforce'
        )->addColumn(
            'username',
            Table::TYPE_TEXT,
            50,
            ['nullable' => true],
            'Name'
        )->addColumn(
            'email',
            Table::TYPE_TEXT,
            50,
            ['nullable' => true],
            'Email'
        )->addColumn(
            'datetime',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Date time'
        )->addColumn(
            'status',
            Table::TYPE_INTEGER,
            1,
            ['nullable' => true],
            'Status'
        );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
