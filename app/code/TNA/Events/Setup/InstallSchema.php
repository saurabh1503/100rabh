<?php


namespace TNA\Events\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $table_tna_continuing_education = $setup->getConnection()->newTable($setup->getTable('tna_continuing_education'));


        $table_tna_continuing_education->addColumn(
            'continuing_education_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );



        $table_tna_continuing_education->addColumn(
            'event_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'The Event Code is a unique identifier for events (courses) in the form
            {YYYY}{MM}{DD}{State}{ProgramCode} Example: 20141113AZRAR'
        );



        $table_tna_continuing_education->addColumn(
            'state_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'State code'
        );



        $table_tna_continuing_education->addColumn(
            'license_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'license_type'
        );



        $table_tna_continuing_education->addColumn(
            'license_label',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'license_label'
        );



        $table_tna_continuing_education->addColumn(
            'credit_hours',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [],
            'credit_hours'
        );



        $table_tna_continuing_education->addColumn(
            'good_standing',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['default' => null],
            'good_standing'
        );


        $setup->getConnection()->createTable($table_tna_continuing_education);

        $setup->endSetup();
    }
}
