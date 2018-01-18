<?php

namespace Fcamodule\Addsample\Setup;

use Magento\Eav\Setup\EavSetup; 
use Magento\Eav\Setup\EavSetupFactory /* For Attribute create  */;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
* @codeCoverageIgnore
*/
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory; 
        /* assign object to class global variable for use in other class methods */
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
         
        /**
         * Add attributes to the eav/attribute
         */
                              
  $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'shipping',
            [
                'group' => 'Shipping',                        
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Shipping',
                'input' => 'text',
                'class' => '',          
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,                  
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false
            ]
        );
                                
                                $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'calculated_shipping',
            [
                'group' => 'Shipping',                        
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Calculated Shipping',
                'input' => 'price',
                'class' => '',          
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,                  
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false
            ]
        );
    }
}
