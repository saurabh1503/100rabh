<?php


namespace TNA\Events\Setup;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Eav\Api\AttributeRepositoryInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    private $categorySetupFactory;
    private $attributeSetFactory;
    private $categoryRepositoryInterface;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepositoryInterface
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory,
        AttributeSetFactory $attributeSetFactory,
        CategoryRepositoryInterface $categoryRepositoryInterface,
        AttributeRepositoryInterface $attributeRepositoryInterface,
        CategoryFactory $categoryFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
        $this->attributeRepositoryInterface = $attributeRepositoryInterface;
        $this->categoryFactory = $categoryFactory;

    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);

        $categoryCollection = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name', "Course Schedule")->setPageSize(1);
        if ($categoryCollection->getSize()) {
             $this->courseScheduleId = $categoryCollection->getFirstItem()->getId();
        } else {
            $category = $categorySetup->createCategory(
                array('data' => [
                    'name' => 'Course Schedule',
                    'is_active' => 1,
                    'meta_title' => 'Course Schedule',
                    'description' => '',
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'sort_order' => 1,
                    'display_mode' => 'PRODUCTS'
                ])
            );
            //$this->categoryRepositoryInterface->validateCategory($category);

            $this->categoryRepositoryInterface->save($category);
        }

        try {
            $eavSetup->getAttributeSet(\Magento\Catalog\Model\Product::ENTITY, 'Event');
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            //  attribute is not exists
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);
            $attributeSet->setData(
                [
                    'attribute_set_name' => 'Event',
                    'entity_type_id' => $entityTypeId,
                    'sort_order' => 1,
                ]
            );
            $attributeSet->validate();
            $attributeSet->save();
            $attributeSet->initFromSkeleton($attributeSetId);
            $attributeSet->save();
            $categorySetup->addAttributeGroup(
                \Magento\Catalog\Model\Product::ENTITY,
                $attributeSet.getAttributeSetId(),
                'Event Details',
                1
            );
            $eavSetup->addAttributeGroup(
                \Magento\Catalog\Model\Product::ENTITY,
                $attributeSet.getAttributeSetId(),
                'Event Location',
                1
            );
            $eavSetup->addAttributeGroup(
                \Magento\Catalog\Model\Product::ENTITY,
                $attributeSet.getAttributeSetId(),
                'Continuing Education',
                1
            );
        }

        $attributes = array(
            'event_hours' =>
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Hours',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array("20","16","8","7","4","3","2","1"))
            ],
            'program_code' =>
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Course',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array("Additional Insureds & Certificates of Insurance","Administering School Risks","Advanced Employment Practices Liability Insurance","Advanced Risk Management Seminar","Agency Management Institute","Agency Operations","Analysis of Risk","Business Auto Policy","Commercial Casualty I","Commercial Casualty II","Commercial Casualty Institute","Commercial Event Details Liability","Commercial Multiline Institute","Commercial Property Institute","Contractors Seminar","Control of Risk","Disability Income & Long Term Care Insurance","Dynamics of Company/Agency Relationships","Dynamics of Sales Management","Dynamics of Selling","Dynamics of Service","Elements of Risk Management","Employment Practices Liability","Entrepreneurial Insurance Symposium 16 Hour","Evaluating and Protecting The Lifestyle","Executive Risk Seminar","Financing of Risk","Fundamentals of Risk Management","Funding School Risks","Handling School Risks","Health Insurance","Homeowners Property Endorsements","Insurance Company Operations","Insuring Commercial Property","Insuring Flood Exposures NFIP Review","Insuring Personal Auto Exposures","Insuring Personal Residential Property","Insuring Toys","Introduction to Commercial Casualty Insurance","Introduction to Commercial Miscellaneous Exposures and Coverages","Introduction to Commercial Property Insurance","Introduction to Employee Benefit Health Care","Introduction to Employee Benefit Retirement Plans","Introduction to Employee Benefits - An Overview","Introduction to Life and Health Insurance","Introduction to Personal Automobile Insurance","Introduction to Personal Residential Insurance","Introduction to Property & Casualty Insurance","Large Commercial Seminar","Legal & Ethical Requirements of Insurance Professionals","Legal Concepts Seminar","Life & Health Essentials","Life & Health Institute","Life Insurance","Liquor Liability","MEGA Seminar","Measuring School Risks","Personal Client Risk Management","Personal Lines - Miscellaneous","Personal Lines Institute","Practical Application of PRM","Practice of Risk Management","Preparing for CIC Homeowners","Preparing for CIC Personal Auto","Principles of Risk Management","Professional Liability Concepts","Risk Management Advanced Topic","Ruble Cyber Risk Seminar","Ruble Food & Beverage Seminar","Ruble Graduate Seminar","Spoilage","Utilities and Ordinance or Law","Standard Behavior & Practices New Mexico","Texas Ethics and Consumer Protection","Understanding Coverage Differences","William T. Hold Seminar","William T. Hold Seminar Commercial","William T. Hold Seminar Personal","William T. Hold Seminar Risk","Winning the Business","Workers Compensation","Workers Compensation Specialist"))
            ],
            'program_group' =>
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Program',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array("Certified Insurance Counselors Institutes","Certified Insurance Service Representatives Courses","Certified Personal Risk Manager Courses","Certified Risk Managers Courses","Certified School Risk Managers Courses","Ethics Courses","Dynamics Series Courses","Other","James K. Ruble Seminars","Dynamics of Service Courses"))
            ],
            'program_group_abbr' =>
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Program Abbreviation',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array("CIC","CISR","CPRM","CRM","CSRM","ETHICS","NCIM","OTHER","RUBLE","SRMA"))
            ],
            'learning_options' =>
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Learning Option',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array("Online Instructor-Led","Online Self-Paced","Traditional Classroom"))
            ],
            'event_type' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event Type',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'licensee_url' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Licensee Url',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'univ_course_type' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'University Course Type',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'univ_course_number' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'University Course Number',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'univ_faculty' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'University Faculty',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'univ_url' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'University Url',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'event_start_date' =>
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'Start Date',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'event_end_date' =>
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'End Date',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'gate_keeper_html' =>
            [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'Gate Keeper Html',
                'input' => 'textarea',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'time_range' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Time Range',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Details',
                'option' => array('values' => array(""))
            ],
            'location_name' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event Location Name',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array(""))
            ],
            'location_line1' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event Address Line 1',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array(""))
            ],
            'location_line2' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event Address Line 2',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array(""))
            ],
            'location_city' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event City',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array(""))
            ],
            'location_state' =>
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Location',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array("(Self-Paced Online Courses)","(Instructor Led Online Courses)","Mexico","Alaska","Alabama","Arkansas","Arizona","California","Colorado","Connecticut","District of Columbia","Delaware","Florida","Georgia","Hawaii","Iowa","Idaho","Illinois","Indiana","Kansas","Kentucky","Louisiana","Massachusetts","Maryland","Maine","Michigan","Minnesota","Missouri","Mississippi","Montana","North Carolina","North Dakota","Nebraska","New Hampshire","New Jersey","New Mexico","Nevada","New York","Ohio","Oklahoma","Oregon","Pennsylvania","Puerto Rico","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Virginia","US Virgin Islands","Vermont","Washington","Wisconsin","West Virginia","Wyoming"))
            ],
            'location_country' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event Country',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array(""))
            ],
            'location_country_code' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event Country Code',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array(""))
            ],
            'location_zip' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event Zip Code',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array(""))
            ],
            'location_phone' =>
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Event Location Phone Number',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Event Location',
                'option' => array('values' => array(""))
            ],
            'license_type' =>
            [
                'type' => 'varchar',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'frontend' => '',
                'label' => 'CE License Type',
                'input' => 'multiselect',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Continuing Education',
                'option' => array('values' => array("GEN","BR,C3,PA,PC","MADJ","GEN,ETH,LU","PC","ADJ","GEN,ETH,FLD","ADJ,ADJ ETH","PC,ETH","GEN,ETH","ETH","PADJ","LH","WADJ","ADJ OPT","BR,C3,PC,PA","GEN,FLD","LAH,ETH","ADJ,ETH","WCADJ","LRE,LH","PC,LRE","ADJ/ETH","ADJ,LU,ETH","GEN,LU,ETH","ADJ,ETH,LU","BR,C3,PC,PA","C1,LA,LB,BR,C3,PA,PC","LH,ETH","LH,LRE","LH/PC","LB,C1,LSB,LHA","LU,GEN,ETH","ADJGEN,LU,ETH","PC/LH/ADJ","LRE,ETH","PROGEN,LU,ETH","AGENT","ADJUSTER","PRO","PC,PC,GEN","ADJGEN","LA,C1,LB,LSB","BR,C3,PA,PC,LA,C1,LB,LSB","BR,C3,PA,PC","BR,LB,LAH,C1,LB,LSB,PC","BR,L3,PA,PC,LA,C1,LB,LSB","PC/LH,ETH","PROGEN","PC,FLD","ETH,GEN","ADJ,GEN","ETH,LU,ADJ","PC/ETH","ETH,LU,GEN","GEN,GEN","PC/LH","ADJ,ADJ","LH/ETH"))
            ],
            'license_state' =>
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'CE License State',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 2,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => true,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'Continuing Education',
                'option' => array('values' => array("Alaska","Alabama","Arkansas","Arizona","California","Colorado","Connecticut","District of Columbia","Delaware","Florida","Georgia","Hawaii","Iowa","Idaho","Illinois","Indiana","Kansas","Kentucky","Louisiana","Massachusetts","Maryland","Maine","Michigan","Minnesota","Missouri","Mississippi","Montana","North Carolina","North Dakota","Nebraska","New Hampshire","New Jersey","New Mexico","Nevada","New York","Ohio","Oklahoma","Oregon","Pennsylvania","Puerto Rico","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Virginia","Vermont","Washington","Wisconsin","West Virginia","Wyoming"))
            ]
        );
        foreach($attributes as $attributeCode => $attributeData) {
            $this->addCustomAttribute($attributeCode, $attributeData, $eavSetup);
        }
        $setup->endSetup();
    }
    private function addCustomAttribute(
        $attributeCode,
        $attributeData,
        $eavSetup
    ) {
        try {
            $attribute = $this->attributeRepositoryInterface->get(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $attributeCode,
                $attributeData
            );
        }
        return;
    }
}
