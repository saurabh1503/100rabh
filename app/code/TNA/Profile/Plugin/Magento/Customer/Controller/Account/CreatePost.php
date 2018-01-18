<?php


namespace TNA\Profile\Plugin\Magento\Customer\Controller\Account;

use Magento\Customer\Api\CustomerRepositoryInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class CreatePost
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
    public function beforeExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject
    ) {
        $this->logger->addInfo("[TNA_Profile][CreatePost] Success ");
        $customer = $subject->customerExtractor->extract('customer_account_create', $subject->_request);
        $this->logger->addInfo("[TNA_Profile][CreatePost] Success " . $customer->getName());

    }
}
