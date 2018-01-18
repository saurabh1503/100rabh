<?php

namespace TNA\Events\Cron;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as AttributeSetCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Model\CategoryFactory;
use TNA\Events\Api\ContinuingEducationRepositoryInterface;

class EventSync
{
    protected $logger;
    protected $courseScheduleId;
    protected $attributeSetEventId;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        ProductInterfaceFactory $ProductInterfaceFactory,
        ProductRepositoryInterface $ProductRepositoryInterface,
        \Magento\Framework\App\Filesystem\DirectoryList $DirectoryList,
        AttributeSetCollectionFactory $AttributeSetCollectionFactory,
        SearchCriteriaBuilder $SearchCriteriaBuilder,
        CategoryFactory $CategoryFactory,
        ContinuingEducationRepositoryInterface $ContinuingEducationRepositoryInterface,
        \TNA\Events\Helper\AttributeHelper $attributeHelper
    ) {
        $this->logger = $logger;
        $this->productInterfaceFactory = $ProductInterfaceFactory;
        $this->productRepositoryInterface = $ProductRepositoryInterface;
        $this->directoryList = $DirectoryList;
        $this->attributeSetCollectionFactory = $AttributeSetCollectionFactory;
        $this->searchCriteriaBuilder = $SearchCriteriaBuilder;
        $this->categoryFactory = $CategoryFactory;
        $this->continuingEducationRepository = $ContinuingEducationRepositoryInterface;
        $this->attributeHelper = $attributeHelper;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $categoryCollection = $this->categoryFactory->create()->getCollection()->addAttributeToFilter('name', "Course Schedule")->setPageSize(1);
        if ($categoryCollection->getSize()) {
             $this->courseScheduleId = $categoryCollection->getFirstItem()->getId();
        }
        $attributeSetCollection = $this->attributeSetCollectionFactory->create()->addFieldToFilter('attribute_set_name', "Event")->setPageSize(1);
        if ($attributeSetCollection->getSize()) {
             $this->attributeSetEventId = $attributeSetCollection->getFirstItem()->getId();
        }

        include($this->directoryList->getPath('app')."/code/TNA/Events/.env.php");

        $conn = oci_connect(getenv("ORA_USER"), getenv("ORA_PW"), getenv("ORA_CS"));
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $eventSql = file_get_contents($this->directoryList->getPath('app')."/code/TNA/Events/sql/eventSync.sql");
        $stid = oci_parse($conn, $eventSql);

        oci_execute($stid);
        $i = 1;
        while ($event = oci_fetch_object($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            if (($i % 10) == 0) {
                $this->logger->addInfo("[TNA_Events][EventSync] " . $i . ' ' . $event->EVE_EVENT_CODE);
            };
            $event->CE = $this->getCe($conn, $event->EVE_EVENT_CODE);
            $this->updateEvents($event);
            $i += 1;
        }
        oci_free_statement($stid);
        oci_close($conn);
        $this->disablePastEvents();
        $this->logger->addInfo("[TNA_Events][EventSync] Cronjob EventSync is executed.");
    }

    private function disablePastEvents()
    {
        $currentDate = (new \DateTime());
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('event_end_date', $currentDate, 'lt')
            ->addFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED, 'eq')
            ->create();

        $pastEvents = $this->productRepositoryInterface->getList($searchCriteria)->getItems();
        //$appState = $objectManager->get("Magento\Framework\App\State");
        //$appState->setAreaCode("webapi_rest");
        foreach ($pastEvents as $pastEvent) {
            $pastEvent->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);

            try {
                $this->productRepositoryInterface->save($pastEvent);
                $this->logger->addInfo("[TNA_Events][EventSync] " . "Disabled:  " . $pastEvent->getId());
            } catch (Exception $e) {
                $this->logger->addInfo("[TNA_Events][EventSync] " . $pastEvent->getId() . $e->getMessage());
            }
        }
    }

    private function getCe($conn, $EVE_EVENT_CODE)
    {
        $ceSql = file_get_contents($this->directoryList->getPath('app')."/code/TNA/Events/sql/eventCeApproval.sql");
        $stidCE = oci_parse($conn, $ceSql);
        oci_bind_by_name($stidCE, ":event_code_bv", $EVE_EVENT_CODE);
        oci_execute($stidCE);
        oci_fetch_all($stidCE, $eventCE);//, OCI_ASSOC + OCI_RETURN_NULLS);
        oci_free_statement($stidCE);
        return $eventCE;
    }
    private function updateEvents($event)
    {
        $product = $this->productInterfaceFactory->create();

        $product->setSku($event->EVE_EVENT_CODE); // Set your sku here
        $product->setId($event->EVE_RID); // Set your sku here
        $product->setName(Trim($event->PRG_DESCRIPTION)); // Name of Product
        $product->setAttributeSetId($this->attributeSetEventId);
        $product->setCategoryIds([2, $this->courseScheduleId]);
        $product->setWebsiteIds([1]);

        $product->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        // $product->setTaxClassId(0); // Tax class id
        $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL); // type of product (simple/virtual/downloadable/configurable)
        $product->setPrice($event->PRICE); // price of product
        $product->setDescription($event->PRG_ONLINE_DESCRIPTION);
        $product->setShortDescription($this->createDescription($event->AGENDA_URL, $event->GENERALINFOHTML));
        $product->setMetaTitle(Trim($event->PRG_DESCRIPTION));
        //$product->setMetaKeyword();
        //$product->setMetaDescription();
        $product->setUrlKey($event->EVE_EVENT_CODE);

        $product->setCustomAttribute('event_hours', $this->attributeHelper->getOptionId('event_hours', $event->EVENT_HOURS));
        $product->setCustomAttribute('program_code', $this->attributeHelper->getOptionId('program_code', Trim($event->PRG_DESCRIPTION)));
        $product->setCustomAttribute('program_group', $this->attributeHelper->getOptionId('program_group', $this->lookupProgramByCode($event->PRG_DES)));
        $product->setCustomAttribute('program_group_abbr', $this->attributeHelper->getOptionId('program_group_abbr', $event->PRG_DES));
        $product->setCustomAttribute('learning_options', $this->attributeHelper->getOptionId('learning_options', $this->getLearningOption($event->LOC_STATE)));
        $product->setCustomAttribute('event_type', $event->EVENTTYPE);
        $product->setCustomAttribute('licensee_url', $event->LICENSEE_URL);
        $product->setCustomAttribute('univ_course_type', $event->UNIV_COURSE_TYPE);
        $product->setCustomAttribute('univ_course_number', $event->UNIV_COURSE_NUMBER);
        $product->setCustomAttribute('univ_faculty', $event->UNIV_PROFESSOR);
        $product->setCustomAttribute('univ_url', $event->LOCATION_UNIV_URL);
        $product->setCustomAttribute('event_start_date', $event->EVE_START_DATE);
        $product->setCustomAttribute('event_end_date', $event->EVE_END_DATE);
        $product->setCustomAttribute('gate_keeper_html', $this->convertMarkupToHtml($event->GATE_KEEPER_HTML));


        $product->setCustomAttribute('location_name', $event->LOC_NAME);
        $product->setCustomAttribute('location_line1', $event->LOC_ADDRESS);
        //$product->setCustomAttribute('location_line2', $event->);
        $product->setCustomAttribute('location_city', $event->LOC_CITY);
        $product->setCustomAttribute('location_state', $this->attributeHelper->getOptionId('location_state', $event->STATE_NAME));
        //$product->setCustomAttribute('location_country', $event->);
        //$product->setCustomAttribute('location_country_code', $event->);
        //$product->setCustomAttribute('location_phone', $event->);
        $product->setCustomAttribute('location_zip', $event->LOC_ZIP_CODE);

        //$product->setCustomAttribute('license_type', $this->attributeHelper->getOptionId('license_type', $event->CE->LICENSE_TYPE));
        //product->setCustomAttribute('license_state', $this->attributeHelper->getOptionId('license_state', $event->CE->STATE_NAME));

        $product->setStockData(
            array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock' => 1, //manage stock
                'is_in_stock' => 1, //Stock Availability
                'qty' => $event->SEATS_ARE_AVAILABLE //qty
            )
        );

        try {
            $this->productRepositoryInterface->save($product);
            $this->logger->addInfo("[TNA_Events][EventSync] " . "Saved " . $event->EVE_EVENT_CODE);
        } catch (Exception $e) {
            $this->logger->addInfo("[TNA_Events][EventSync] " . $event->EVE_EVENT_CODE . $e->getMessage());
        }

        return;
    }

    private function getLearningOption($state)
    {
        switch ($state) {
            case "WB":
                return "Online Instructor-Led";
                break;
            case "OL":
                return "Online Self-Paced";
                break;
            default:
                return "Traditional Classroom";
        };
    }
    private function convertMarkupToHtml($str)
    {
        return str_replace(
            array(
                '[br]',
                '[/br]',
                '[br ]',
                '[br /]',
                '[b]',
                '[/b]',
                '[/a]',
                '[a href',
                '[strong]',
                '[/strong]',
                '[h1]',
                '[/h1]',
                '[h2]',
                '[/h2]',
                '[h3]',
                '[/h3]',
                '[p]',
                '[/p]',
                '[u]',
                '[/u]',
                '"]'
            ),
            array(
                '<br>',
                '</br>',
                '<br>',
                '</br>',
                '<b>',
                '</b>',
                '</a>',
                '<a href',
                '<strong>',
                '</strong>',
                '<h1>',
                '</h1>',
                '<h2>',
                '</h2>',
                '<h3>',
                '</h3>',
                '<p>',
                '</p>',
                '<u>',
                '</u>',
                '\"\>'
            ),
            $str
        );
    }

    private function createDescription($AGENDA_URL, $GENERALINFOHTML)
    {
        $string = $AGENDA_URL != 'NA' ? '<a href="' . $AGENDA_URL .
                    '"" style="color: #7D0040;  text-decoration: underline;" target="_blank">Download Agenda</a><br>' : '';
        $string .= $this->convertMarkupToHtml($GENERALINFOHTML);
        return $string;
    }
    private function lookupProgramByCode($programCode)
    {
        $programCodeMap = array(
            "CIC"=>"Certified Insurance Counselors Institutes",
            "CISR"=>"Certified Insurance Service Representatives Courses",
            "CPRM"=>"Certified Personal Risk Manager Courses",
            "CRM"=>"Certified Risk Managers Courses",
            "CSRM"=>"Certified School Risk Managers Courses",
            "ETHICS"=>"Ethics Courses",
            "NCIM"=>"Dynamics Series Courses",
            "OTHER"=>"Other" ,
            "RUBLE"=>"James K. Ruble Seminars",
            "SRMA"=>"Dynamics of Service Courses"
        );
        return $programCodeMap[$programCode];
    }
}
