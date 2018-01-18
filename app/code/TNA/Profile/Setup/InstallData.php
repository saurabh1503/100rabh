<?php


namespace TNA\Profile\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class InstallData implements InstallDataInterface
{

    private $customerSetupFactory;
    private $attributeRepositoryInterface;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeRepositoryInterface $attributeRepositoryInterface,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeRepositoryInterface = $attributeRepositoryInterface;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $setup->startSetup();
        $attributesInfo = [
            'participant_id' => array(
                'data' => [
                            'label' => 'Alliance Member Number',
                            'type' => 'varchar',
                            'input' => 'text',
                            'position' => 1000,
                            'visible' => true,
                            'required' => false,
                            'system' => 0,
                            'user_defined' => false,
                            'position' => 1000,
                        ],
                'formData' =>   [
                                'adminhtml_customer'
                            ]
            ),
            'name_preference' => array(
                'data' => [
                            'type' => 'varchar',
                            'label' => 'Name Preference',
                            'input' => 'text',
                            'source' => '',
                            'required' => false,
                            'visible' => true,
                            'position' => 71,
                            'sort_order' => 71,
                            'system' => 0,
                            'backend' => ''
                        ],
                'formData' => [
                                'adminhtml_customer',
                                'adminhtml_checkout',
                                'customer_account_edit'
                            ]
            ),/*
            'username' => array(
                'data' => [
                            'type' => 'varchar',
                            'label' => 'Username',
                            'input' => 'text',
                            'source' => '',
                            'required' => false,
                            'visible' => true,
                            'position' => 130,
                            'sort_order' => 130,
                            'system' => false,
                            'backend' => ''
                        ],
                'formData' => [
                                'adminhtml_customer',
                                'adminhtml_checkout',
                                'customer_account_edit'
                            ]
            ),*/
            'professional_focus' => array(
                'data' => [
                            'type' => 'int',
                            'label' => 'Professional Focus ',
                            'input' => 'select',
                            'source' => 'TNA\Profile\Model\Customer\Attribute\Source\ProfessionalFocus',
                            'required' => false,
                            'visible' => true,
                            'position' => 160,
                            'sort_order' => 160,
                            'system' => 0,
                            'default' => '0',
                            'backend' => ''
                        ],
                'formData' => [
                                'adminhtml_customer',
                                'adminhtml_checkout',
                                'customer_account_edit'
                            ]
            ),
            'employer_description' => array(
                'data' => [
                            'type' => 'int',
                            'label' => 'Employer Description',
                            'input' => 'select',
                            'source' => 'TNA\Profile\Model\Customer\Attribute\Source\EmployerDescription',
                            'default' => '0',
                            'required' => false,
                            'visible' => true,
                            'position' => 170,
                            'sort_order' => 170,
                            'system' => 0,
                            'backend' => ''
                        ],
                'formData' => [
                                'adminhtml_customer',
                                'adminhtml_checkout',
                                'customer_account_edit'
                            ]
            ),/*
            'alliance_designations' => array(
                'data' => [
                            'type' => 'varchar',
                            'label' => 'Alliance Designations',
                            'input' => 'multiselect',
                            'source' => 'TNA\Profile\Model\Customer\Attribute\Source\AllianceDesignations',
                            'required' => false,
                            'visible' => true,
                            'position' => 180,
                            'sort_order' => 180,
                            'system' => false,
                            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend'
                        ],
                'formData' => [
                                'adminhtml_customer',
                                'adminhtml_checkout',
                            ]
            ),
            'national_producer_number' => array(
                'data' => [
                            'type' => 'varchar',
                            'label' => 'National Producer Number ',
                            'input' => 'text',
                            'source' => '',
                            'required' => false,
                            'visible' => true,
                            'position' => 190,
                            'sort_order' => 190,
                            'system' => false,
                            'backend' => ''
                        ],
                'formData' => [
                                'adminhtml_customer',
                                'adminhtml_checkout',
                                'customer_account_edit'
                            ]
            ),
            'state_license_number' => array(
                'data' => [
                            'type' => 'varchar',
                            'label' => 'State License Number',
                            'input' => 'text',
                            'source' => '',
                            'required' => false,
                            'visible' => true,
                            'position' => 200,
                            'sort_order' => 200,
                            'system' => false,
                            'backend' => ''
                        ],
                'formData' => [
                                'adminhtml_customer',
                                'adminhtml_checkout',
                                'customer_account_edit'
                            ]
            ),*/
        ];
        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);


        foreach ($attributesInfo as $attributeCode => $attributeParams) {

            try {
                //$attributeExist = $this->attributeRepositoryInterface->get(\Magento\Customer\Model\Customer::ENTITY, $attributeCode);
                //$this->attributeRepositoryInterface->delete($attributeExist);
                //continue;
                $customerSetup->addAttribute(Customer::ENTITY, $attributeCode, $attributeParams['data']);
                $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, $attributeCode)
                  ->addData([
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => $attributeParams['formData'],
                  ]);
                $attribute->save();
                // $attributeExist = $this->attributeRepositoryInterface->get(\Magento\Customer\Model\Customer::ENTITY, $attributeCode);
                //$this->attributeRepositoryInterface->delete($attributeExist);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        $setup->endSetup();
    }
}
