<?php


namespace TNA\Profile\Plugin\Magento\Customer\Model;

use Magento\Customer\Api\CustomerRepositoryInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class AccountManagement
{
    /**
     * Constructor
     *
     * @param
     */
    public function __construct(
        CustomerRepositoryInterfaceFactory $customerRepositoryInterfaceFactory,
        CustomerInterfaceFactory $customerInterfaceFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Filesystem\DirectoryList $DirectoryList,
        \TNA\Core\Helper\AttributeHelper $attributeHelper,
        \Magento\Framework\App\DeploymentConfig $deploymentConfig
    ) {
        $this->customerRepositoryInterfaceFactory = $customerRepositoryInterfaceFactory;
        $this->customerInterfaceFactory = $customerInterfaceFactory;
        $this->logger = $logger;
        $this->directoryList = $DirectoryList;
        $this->attributeHelper = $attributeHelper;
        $this->deploymentConfig = $deploymentConfig;
    }

    public function beforeAuthenticate(
        \Magento\Customer\Api\AccountManagementInterface $subject,
        $username,
        $password
        //$functionvariables
    ) {
        $customerRepositoryInterface = $this->customerRepositoryInterfaceFactory->create();
        try {
            $customer = $customerRepositoryInterface->get($username);
        } catch (NoSuchEntityException $e) {
            $conn = oci_connect(
                $this->deploymentConfig->get('oracleDb')['ORA_USER'],
                $this->deploymentConfig->get('oracleDb')['ORA_PW'],
                $this->deploymentConfig->get('oracleDb')['ORA_CS']
            );
            if (!$conn) {
                $e = oci_error();
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            $participantSql = file_get_contents($this->directoryList->getPath('app')."/code/TNA/Profile/sql/getParticipantLogin.sql");
            $stid = oci_parse($conn, $participantSql);
            oci_bind_by_name($stid, ":participant_email_bv", $username);
            oci_execute($stid);
            if ($participantData = oci_fetch_object($stid)) {
                $this->createParticipant($username, $password, $subject, $participantData);
                $this->logger->addInfo("[TNA_Profile][AccountManagement][beforeAuthenticate] Success " . $username);
            } else {
                $this->logger->addInfo("[TNA_Profile][AccountManagement][beforeAuthenticate] DNE in oracleDb " . $username);
                return null;
            }
            oci_free_statement($stid);
            oci_close($conn);
        }
    }
    public function beforeCreateAccount(
        \Magento\Customer\Api\AccountManagementInterface $subject,
        $customer,
        $password
    ) {
        $this->logger->addInfo("[TNA_Profile][AccountManagement][beforeCreateAccount] ");
        return;
        $customerRepositoryInterface = $this->customerRepositoryInterfaceFactory->create();
        $conn = oci_connect(
            $this->deploymentConfig->get('oracleDb')['ORA_USER'],
            $this->deploymentConfig->get('oracleDb')['ORA_PW'],
            $this->deploymentConfig->get('oracleDb')['ORA_CS']
        );
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $participantSql = file_get_contents($this->directoryList->getPath('app')."/code/TNA/Profile/sql/getParticipantLogin.sql");
        $stid = oci_parse($conn, $participantSql);
        oci_bind_by_name($stid, ":participant_email_bv", $customer->getEmail());
        oci_execute($stid);
        if ($participantData = oci_fetch_object($stid)) {
            //$this->createParticipant($customer->getEmail(), $password, $subject, $participantData);
            $this->logger->addInfo("[TNA_Profile][AccountManagement] Success " . $customer->getEmail());
        } else {
            $this->logger->addInfo("[TNA_Profile][AccountManagement] DNE in oracleDb " . $customer->getEmail());
            return null;
        }
        oci_free_statement($stid);
        oci_close($conn);
    }

    public function createParticipant(
        $username,
        $password,
        $subject,
        $participantData
    ) {
        try {
            $customerRepositoryInterface = $this->customerRepositoryInterfaceFactory->create();
            $customer = $this->customerInterfaceFactory->create();
            $customer->setEmail($username);
            $customer->setFirstname($participantData->FIRSTNAME);
            $customer->setLastname($participantData->LASTNAME);
            $customer->setDob($participantData->DOB);
            //$customer->setGroupId();
            //$customer->setAddresses();
            $subject->createAccount($customer, $participantData->PASSWORD);
        } catch (\Exception $e) {
            $this->logger->addInfo("[TNA_Profile][AccountManagement] ".  $e->getMessage());
        }
    }
}
