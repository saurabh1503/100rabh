<?php
namespace Magenest\Salesforce\Setup;

use Magento\Framework\Setup\SetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 * @package Magenest\Salesforce\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrade database when run bin/magento setup:upgrade from command line
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '2.0.0') < 0) {
            $this->createRequestTable($installer);
            $this->createQueueTable($installer);
            $this->addReportColumn($installer);
            $this->changeReportColumn($installer);
        }

        $installer->endSetup();
    }

    /**
     * Create the table magenest_salesforce_report
     *
     * @param SetupInterface $installer
     * @return void
     */
    private function addReportColumn($installer)
    {
        $installer->getConnection()->addColumn(
            $installer->getTable('magenest_salesforce_report'),
            'msg',
            [
                'type' => TABLE::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Message'
            ]
        );
    }

    /**
     * Create the table magenest_salesforce_report
     *
     * @param SetupInterface $installer
     * @return void
     */
    private function changeReportColumn($installer)
    {
        $installer->getConnection()->changeColumn(
            $installer->getTable('magenest_salesforce_report'),
            'id_magento',
            'magento_id',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'Magento Id'
            ]
        );
    }

    /**
     * Create the table magenest_salesforce_request
     *
     * @param SetupInterface $installer
     * @return void
     */
    private function createRequestTable($installer)
    {
        $tableName = 'magenest_salesforce_request';
        if ($installer->tableExists($tableName)) {
            return;
        }

        $table = $installer->getConnection()->newTable(
            $installer->getTable($tableName)
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Id'
        )->addColumn(
            'date',
            Table::TYPE_DATE,
            null,
            ['nullable' => false],
            'Date'
        )->addColumn(
            'rest_request',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Request'
        )->addColumn(
            'bulk_request',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Request'
        )->setComment(
            'Salesforce Request Table'
        );

        $installer->getConnection()->createTable($table);
    }

    /**
     * Create the table magenest_salesforce_queue
     *
     * @param SetupInterface $installer
     * @return void
     */
    private function createQueueTable($installer)
    {
        $tableName = 'magenest_salesforce_queue';
        $table = $installer->getConnection()->newTable(
            $installer->getTable($tableName)
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Id'
        )->addColumn(
            'type',
            Table::TYPE_TEXT,
            45,
            ['nullable' => true],
            'Entity Type'
        )->addColumn(
            'entity_id',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Entity Id'
        )->addColumn(
            'enqueue_time',
            Table::TYPE_DATETIME,
            null,
            ['nullable' => true],
            'Enqueue Time'
        )->addColumn(
            'priority',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false],
            'Enqueue Time'
        )->setComment(
            'Salesforce Sync Queue'
        );

        $installer->getConnection()->createTable($table);
    }
}
