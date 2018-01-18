<?php
namespace Magenest\Salesforce\Block\Adminhtml\CustomerEdit\Tab\View;

use Magenest\Salesforce\Model\Connector;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Customer\Model\Customer;

/**
 * Class SalesforceCustomerInfo
 * @package Magenest\Salesforce\Block\Adminhtml\Edit\Tab\View
 */
class SalesforceCustomerInfo extends \Magento\Backend\Block\Template
{
    /**
     * Customer
     *
     * @var \Magento\Customer\Api\Data\CustomerInterface
     */
    protected $customer;

    /**
     * Customer registry
     *
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    protected $customerRegistry;


    /**
     * Customer data factory
     *
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    protected $customerDataFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Data object helper
     *
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var \Magenest\Salesforce\Model\ReportFactory
     */
    protected $logFactory;

    /**
     * SalesforceCustomerInfo constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magenest\Salesforce\Model\ReportFactory $logFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerDataFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magenest\Salesforce\Model\ReportFactory $logFactory,
        array $data = []
    ) {
    
        $this->logFactory = $logFactory;
        $this->coreRegistry = $registry;
        $this->customerDataFactory = $customerDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $data);
    }

    /**
     * Set customer registry
     *
     * @param \Magento\Framework\Registry $coreRegistry
     * @return void
     * @deprecated
     */
    public function setCustomerRegistry(\Magento\Customer\Model\CustomerRegistry $customerRegistry)
    {

        $this->customerRegistry = $customerRegistry;
    }

    /**
     * Get customer registry
     *
     * @return \Magento\Customer\Model\CustomerRegistry
     * @deprecated
     */
    public function getCustomerRegistry()
    {

        if (!($this->customerRegistry instanceof \Magento\Customer\Model\CustomerRegistry)) {
            return \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Customer\Model\CustomerRegistry');
        } else {
            return $this->customerRegistry;
        }
    }

    /**
     * Retrieve customer object
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function getCustomer()
    {
        if (!$this->customer) {
            $this->customer = $this->customerDataFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $this->customer,
                $this->_backendSession->getCustomerData()['account'],
                '\Magento\Customer\Api\Data\CustomerInterface'
            );
        }
        return $this->customer;
    }

    /**
     * Retrieve customer id
     *
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * @return null|string
     */
    public function getCreatedAt()
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', ['IN' => ['Contact', 'Lead', 'Account']])
            ->addFieldToFilter('magento_id', $this->getCustomerId())
            ->getFirstItem();
        return $log->getData('datetime') ? $this->formatDate($log->getData('datetime'), \IntlDateFormatter::MEDIUM, true) : 'Never';
    }

    /**
     * @return null|string
     */
    public function getLastUpdatedAt()
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', ['IN' => ['Contact', 'Lead', 'Account']])
            ->addFieldToFilter('magento_id', $this->getCustomerId())
            ->getLastItem();
        return $log->getData('datetime') ? $this->formatDate($log->getData('datetime'), \IntlDateFormatter::MEDIUM, true) : 'Never';
    }

    /**
     * @param $type
     * @return string
     */
    public function getSalesforceId($type)
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', $type)
            ->addFieldToFilter('magento_id', $this->getCustomerId());
        foreach ($log as $v) {
            if ($v->getData('record_id')) {
                return $v->getData('record_id');
            }
        }
        return '';
    }

    public function getSalesforceUrl($type)
    {
        return $this->_scopeConfig->getValue(Connector::XML_PATH_SALESFORCE_INSTANCE_URL) . '/' . $this->getSalesforceId($type);
    }

    public function getSyncLog()
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', ['IN' => ['Contact', 'Lead', 'Account']])
            ->addFieldToFilter('magento_id', $this->getCustomerId())
            ->addOrder('datetime', 'DESC')
            ->setPageSize(10)
            ->setCurPage(1);

        return $log;
    }

    public function getSyncButtonUrl($type)
    {
        return $this->getUrl('salesforce/sync/' . $type, ['id' => $this->getCustomerId()]);
    }
}
