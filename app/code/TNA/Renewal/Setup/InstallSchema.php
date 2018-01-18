<?php
 
namespace TNA\Renewal\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
 
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
 
        // Get tutorial_simplenews table
        $tableName = $installer->getTable('pay_dues');
        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            // Create tutorial_simplenews table
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'user_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'User Id'
                )
                ->addColumn(
                    'designation',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Designation'
                )
                ->addColumn(
                    'exp_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Expiry Date'
                )
                ->setComment('Dues Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }
 
        $installer->endSetup();
    }
}