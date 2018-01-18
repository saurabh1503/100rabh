<?php
 
namespace Efloor\Requestquote\Setup;
 
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
 
        // Get efloor_requestquote_details table
   
            $table = $installer->getConnection()
                ->newTable('efloor_requestquote_detail')
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
                    'fullname',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Fullname'
                )
                ->addColumn(
                    'firstaddress',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Firstaddress'
                )
                ->addColumn(
                    'secondaddress',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Secondaddress'
                )
                ->addColumn(
                    'city',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'City'
                )
                ->addColumn(
                    'state',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'State'
                )
                ->addColumn(
                    'country',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Country'
                )
                ->addColumn(
                    'zip',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Zip'
                )
                 ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Email'
                )
                ->addColumn(
                    'phone',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Phone'
                )
                ->addColumn(
                    'memo',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Memo'
                ) 
                ->addColumn(
                    'contactby',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Contactby'
                )
                ->addColumn(
                    'created_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created_date'
                ) 
                 ->addColumn(
                    'updated_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated_date'
                ) 
                 ->addColumn(
                    'email_spam',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false],
                    'Email_spam'
                ) 
                 ->addColumn(
                    'url_key',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Url_key'
                ) 
                 
                ->addColumn(
                    'is_active',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Is_active'
                )
                ->setComment('Requestquote detail Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        
      
            $table = $installer->getConnection()
                ->newTable('efloor_requestquote_product')
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
                    'requestquote_detail_id',
                    Table::TYPE_TEXT,
                    null,
                   ['nullable' => false, 'default' => ''],
                   'Requestquote_detail_id'  
                       
                       
                )
                ->addColumn(
                    'product_description',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Product_description'
                )
                ->addColumn(
                    'product_qty',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Product_qty'
                )
                ->addColumn(
                    'created_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created_date'
                ) 
                 ->addColumn(
                    'updated_date',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Updated_date'
                ) 


                ->setComment('Requestquote product Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        
        
       
        $installer->endSetup();
    }

}
