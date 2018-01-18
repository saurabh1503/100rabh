<?php


namespace TNA\Profile\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;

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


        $setup->endSetup();
        return;





        $table_tna_ice = $setup->getConnection()->newTable($setup->getTable('tna_ice'));


        $table_tna_ice->addColumn(
            'ice_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );



        $table_tna_ice->addColumn(
            'participant_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'The name of the participant'
        );



        $table_tna_ice->addColumn(
            'event_attending',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'The event the participant is attending'
        );


        $table_tna_archive = $setup->getConnection()->newTable($setup->getTable('tna_archive'));


        $table_tna_archive->addColumn(
            'archive_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );



        $table_tna_archive->addColumn(
            'document_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'document_name'
        );


        $setup->getConnection()->createTable($table_tna_archive);

        $setup->getConnection()->createTable($table_tna_ice);

    }
}
