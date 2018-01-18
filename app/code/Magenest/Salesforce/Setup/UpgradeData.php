<?php
namespace Magenest\Salesforce\Setup;

use Magento\Customer\Model\Customer;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Sales\Model\Order;
use Magento\Sales\Setup\SalesSetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * Sales setup factory
     *
     * @var SalesSetupFactory
     */
    protected $salesSetupFactory;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '2.0.0') < 0) {
            $this->createCustomerAttribute($setup);
            $this->createProductAttribute($setup);
            $this->createOrderAttribute($setup);
        }
        $setup->endSetup();
    }

    private function createCustomerAttribute($setup)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);

        $customerSetup->addAttribute(
            Customer::ENTITY,
            'salesforce_account_id',
            [
            'type' => 'text',
            'label' => 'Salesforce Account Id',
            'input' => 'text',
            'required' => false,
            'visible' => false,
            'user_defined' => false,
            'position' =>999,
            'system' => 0,
            ]
        );

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'salesforce_account_id')
            ->addData([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer'],
            ]);

        $attribute->save();
    }

    private function createProductAttribute($setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            'salesforce_product_id',
            array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false,
                'label' => 'Salesforce Product Id',
                'system' => false
            )
        );
        $eavSetup->addAttribute(
            Product::ENTITY,
            'salesforce_pricebookentry_id',
            array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false,
                'label' => 'Salesforce PricebookEntry Id',
                'system' => false,
            )
        );
    }

    private function createOrderAttribute($setup)
    {
        /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

        $salesSetup->addAttribute(
            Order::ENTITY,
            'salesforce_order_id',
            [
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false,
                'label' => 'Salesforce Order Id',
                'system' => false,
            ]
        );
    }
}
