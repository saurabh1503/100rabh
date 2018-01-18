<?php


namespace TNA\Profile\Observer\Customer;

use \Magento\Customer\Api\CustomerRepositoryInterfaceFactory;

class SaveAfterDataObject implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerRepositoryInterfaceFactory $customerRepositoryInterfaceFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Filesystem\DirectoryList $DirectoryList,
        \TNA\Core\Helper\AttributeHelper $attributeHelper,
        \Magento\Framework\App\DeploymentConfig $deploymentConfig,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $this->customerRepositoryInterfaceFactory = $customerRepositoryInterfaceFactory;
        $this->logger = $logger;
        $this->directoryList = $DirectoryList;
        $this->attributeHelper = $attributeHelper;
        $this->deploymentConfig = $deploymentConfig;
        $this->jsonHelper = $jsonHelper;
    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Customer\Model\Customer $customer */
        $this->logger->addInfo("[TNA_Profile][SaveAfterDataObject] Success ");

        return;
        $customerRepositoryInterface = $this->customerRepositoryInterfaceFactory->create();

        $savedCustomer = $observer->getEvent()->getData('customer_data_object');
        $customer = $observer->getEvent()->getData('orig_customer_data_object');

        $customer = $customerRepositoryInterface->get($customer->getEmail());
        //$this->updateParticipantOracle($customer, $participantData);

    }

    private function updateParticipantOracle(
        $customer
    ) {
        $conn = oci_connect(
            $this->deploymentConfig->get('oracleDb')['ORA_USER'],
            $this->deploymentConfig->get('oracleDb')['ORA_PW'],
            $this->deploymentConfig->get('oracleDb')['ORA_CS']
        );
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $this->logger->addInfo("[TNA_Profile][SaveAfterDataObject] Success " . $customer->getEmail());

        oci_free_statement($stid);
        oci_close($conn);



        try {
            $customerRepositoryInterface = $this->customerRepositoryInterfaceFactory->create();
            $customer->setPrefix($participantData->PREFIX);
            $customer->setFirstname($participantData->FIRSTNAME);
            $customer->setMiddlename($participantData->MIDDLENAME);
            $customer->setLastname($participantData->LASTNAME);
            $customer->setDob($participantData->DOB);
            $customer->setSuffix($participantData->SUFFIX);
            $customer->setGender($participantData->GENDER);
            $customer->setCustomAttribute('participant_id', $participantData->PARTICIPANT_ID);
            $customer->setCustomAttribute('name_preference', $participantData->NAME_PREFERENCE);
            $customer->setCustomAttribute('professional_focus', $participantData->PROFESSIONAL_FOCUS);
            $customer->setCustomAttribute('employer_description', $participantData->EMPLOYER_DESCRIPTION);
            //$customer->setGroupId();
            //$customer->setAddresses();

            $customerRepositoryInterface->save($customer);
        } catch (\Exception $e) {
            $this->logger->addInfo("[TNA_Profile][SaveAfterDataObject] ".  $e->getMessage());
        }
    }
    /**
     * @param array $dataToEncode
     * @return string
     */
    public function encodeParticipant(array $participantData)
    {
        $encodedData = $this->jsonHelper->jsonEncode($participantData);
        return $encodedData;
    }
}
