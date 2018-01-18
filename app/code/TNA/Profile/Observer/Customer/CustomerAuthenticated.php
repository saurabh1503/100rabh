<?php

namespace TNA\Profile\Observer\Customer;

use \Magento\Customer\Api\CustomerRepositoryInterfaceFactory;
use \Magento\Customer\Api\AddressRepositoryInterfaceFactory;
use \Magento\Customer\Api\Data\AddressInterfaceFactory;
use \Magento\Directory\Api\CountryInformationAcquirerInterfaceFactory;
use \Magento\Directory\Model\Region;

class CustomerAuthenticated implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerRepositoryInterfaceFactory $customerRepositoryInterfaceFactory,
        AddressRepositoryInterfaceFactory $addressRepositoryInterfaceFactory,
        AddressInterfaceFactory $addressInterfaceFactory,
        Region $region,
        CountryInformationAcquirerInterfaceFactory $countryInformationAcquirerInterfaceFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Filesystem\DirectoryList $DirectoryList,
        \TNA\Core\Helper\AttributeHelper $attributeHelper,
        \Magento\Framework\App\DeploymentConfig $deploymentConfig
    ) {
        $this->customerRepositoryInterfaceFactory = $customerRepositoryInterfaceFactory;
        $this->addressRepositoryInterfaceFactory = $addressRepositoryInterfaceFactory;
        $this->addressInterfaceFactory = $addressInterfaceFactory;
        $this->region = $region;
        $this->countryInformationAcquirerInterfaceFactory = $countryInformationAcquirerInterfaceFactory;
        $this->logger = $logger;
        $this->directoryList = $DirectoryList;
        $this->attributeHelper = $attributeHelper;
        $this->deploymentConfig = $deploymentConfig;
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
        $customerRepositoryInterface = $this->customerRepositoryInterfaceFactory->create();

        $customer = $observer->getEvent()->getData('model');
        $customer = $customerRepositoryInterface->get($customer->getEmail());

        //$customerRepositoryInterface->save($customer);

        $conn = oci_connect(
            $this->deploymentConfig->get('oracleDb')['ORA_USER'],
            $this->deploymentConfig->get('oracleDb')['ORA_PW'],
            $this->deploymentConfig->get('oracleDb')['ORA_CS']
        );
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $participantSql = file_get_contents($this->directoryList->getPath('app')."/code/TNA/Profile/sql/getParticipant.sql");
        $stid = oci_parse($conn, $participantSql);
        $participantEmail = $customer->getEmail();
        oci_bind_by_name($stid, ":participant_email_bv", $participantEmail);
        oci_execute($stid);
        $this->logger->addInfo("[TNA_Profile][CustomerAuthenticated] Success " . $customer->getEmail());
        $participantData = oci_fetch_object($stid);//, OCI_ASSOC + OCI_RETURN_NULLS);
        $this->updateParticipantMagento($customer, $participantData);
        oci_free_statement($stid);
        oci_close($conn);
    }

    private function updateParticipantMagento(
        $customer,
        $participantData
    ) {
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
            $customer->setCustomAttribute('professional_focus',
                (!isset($participantData->PROFESSIONAL_FOCUS) || is_null($participantData->PROFESSIONAL_FOCUS)) ? 0 : $participantData->PROFESSIONAL_FOCUS);
            $customer->setCustomAttribute('employer_description',
                (!isset($participantData->EMPLOYER_DESCRIPTION) || is_null($participantData->EMPLOYER_DESCRIPTION)) ? 0: $participantData->EMPLOYER_DESCRIPTION);

            //$customer->setGroupId();
            $addresses = $this->getParticipantAddress($customer, $participantData);

            $customer->setAddresses($addresses);
            $customerRepositoryInterface->save($customer);

        } catch (\Exception $e) {
            $this->logger->addError("[TNA_Profile][CustomerAuthenticated] ".  $e->getMessage());
        }
    }

    private function getParticipantAddress(
        $customer,
        $participantData
    ) {
        $this->logger->addInfo("[TNA_Profile][CustomerAuthenticated][getParticipantAddress] ");
        $addressRepositoryInterface = $this->addressRepositoryInterfaceFactory->create();
        $addresses = [
            'homeAddress' => [
                'state' => $participantData->HOMESTATE,
                'country' => $participantData->HOMECOUNTRY,
                'phone' => trim($participantData->HOMEPHONE),
                'cellPhone' => trim($participantData->CELLPHONE),
                'fax' => $participantData->FAX,
                'city' => $participantData->HOMECITY,
                'zip' => $participantData->HOMEZIP,
                'address1' => $participantData->HOMEADDRESS1,
                'address2' => $participantData->HOMEADDRESS2,
                'address3' => $participantData->HOMEADDRESS3,
                'defaultShipping' => true,
                'defaultBilling' => false,
                'addressInterface' =>  $customer->getDefaultShipping()

            ],
            'businessAddress' => [
                'state' => $participantData->BUSINESSSTATE,
                'country' => $participantData->BUSINESSCOUNTRY,
                'phone' => trim($participantData->BUSINESSPHONE),
                'cellPhone' => trim($participantData->CELLPHONE),
                'fax' => $participantData->FAX,
                'city' => $participantData->BUSINESSCITY,
                'zip' => $participantData->BUSINESSZIP,
                'address1' => $participantData->BUSINESSADDRESS1,
                'address2' => $participantData->BUSINESSADDRESS2,
                'address3' => $participantData->BUSINESSADDRESS3,
                'defaultShipping' => false,
                'defaultBilling' => true,
                'addressInterface' => $customer->getDefaultBilling()
            ]
        ];


        foreach($addresses as $type => $data) {
            try {
                if(is_null($data['addressInterface'])) {
                    $addressInterface = $this->addressInterfaceFactory->create();
                } else {
                    $addressInterface = $addressRepositoryInterface->getById($data['addressInterface']);
                }
                $regionId = $this->region->loadByCode($data['state'], $data['country'])->getId();
                $phone = (!isset($data['phone']) || is_null($data['phone'])) ? $data['cellPhone'] : $data['phone'];
                $phone = (!isset($phone) || is_null($phone) || '' != $phone) ? 013 : $phone ;
                $this->logger->addInfo("[TNA_Profile][CustomerAuthenticated][getParticipantAddress] " .  $data['phone'] . $data['addressInterface']   );

                $addressInterface->setRegionId($regionId);
                $addressInterface->setCountryId($data['country']);
                $addressInterface->setTelephone(trim($phone) . '1');
                $addressInterface->setFax(trim($data['cellPhone']));
               // $addressInterface->setCompany($participantData->);
                $addressInterface->setCity($data['city']);
                $addressInterface->setPostcode($data['zip']);
                $addressInterface->setStreet([$data['address1'], $data['address2'].$data['address3']]);

                $addressInterface->setPrefix($participantData->PREFIX);
                $addressInterface->setFirstname($participantData->FIRSTNAME);
                $addressInterface->setMiddlename($participantData->MIDDLENAME);
                $addressInterface->setLastname($participantData->LASTNAME);
                $addressInterface->setSuffix($participantData->SUFFIX);
                $addressInterface->setIsDefaultShipping((bool)$data['defaultShipping']);
                $addressInterface->setIsDefaultBilling($data['defaultBilling']);
                $addressInterface->setCustomerId($customer->getId());
                $addressRepositoryInterface->save($addressInterface);
            } catch (\Exception $e) {
                $this->logger->addError("[TNA_Profile][CustomerAuthenticated][getParticipantAddress] ".  $e->getMessage());
            }
           // return [$addArray];
        }
    }
}