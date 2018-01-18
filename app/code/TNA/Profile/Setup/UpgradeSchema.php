<?php


namespace TNA\Profile\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

     public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
      
	  $setup->startSetup();
	  if (version_compare($context->getVersion(), "1.0.1", "<")) {
			$installer = $setup;
			$installer->startSetup();

			$table = $installer->getConnection()
				->newTable($installer->getTable('incaseof_emergency'))
				->addColumn('id',
							\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
							11,
							['nullable' => false,'unsigned' => true,'unsigned' => true,'auto_increment' => true, 'primary' => true],
							'Id'
							)
				->addColumn('customer_id',
							\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
							11,
							['nullable' => true,'default' => null],
							'Customer Id'
							)			
				->addColumn('name',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Name'
							)
                 ->addColumn('attending',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Attending'
							)
							
				->addColumn('address_program',
							\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
							null,
							['nullable' => true,'default' => null],
							'Address Program'
							)

				->addColumn('address',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Address'
							)
                ->addColumn('phone',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Phone'
							) 
				
				->addColumn('name_primary',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Primary Name'
							)
				->addColumn('relationship',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Relationship'
							)

				->addColumn('address_pr',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Primary Address'
							)	

				->addColumn('phone_pr',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Primary Phone'
							)

				->addColumn('medication',
							\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
							255,
							['nullable' => true,'default' => null],
							'Medication'
							);
				
			$installer->getConnection()->createTable($table);
			$installer->endSetup();
		}
	  }
}


