<?php

namespace Magenest\Salesforce\Block\Adminhtml\ProductEdit\Tab\View;

use Magenest\Salesforce\Model\Connector;
use Magento\Customer\Model\Customer;

/**
 * Class SalesforceCustomerInfo
 * @package Magenest\Salesforce\Block\Adminhtml\Edit\Tab\View
 */
class SalesforceProductInfo extends \Magento\Backend\Block\Template
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magenest\Salesforce\Model\ReportFactory
     */
    protected $logFactory;

    /**
     * SalesforceItemInfo constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magenest\Salesforce\Model\ReportFactory $logFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magenest\Salesforce\Model\ReportFactory $logFactory,
        array $data = []
    ) {
        $this->logFactory = $logFactory;
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve currently edited product object
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->coreRegistry->registry('current_product');
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->getProduct()->getId();
    }

    /**
     * @return int
     */
    public function getSku()
    {
        return $this->getProduct()->getSku();
    }

    /**
     * Get customer creation date
     *
     * @return string
     */
    public function getCreatedAt()
    {
        $log = $this->logFactory->create()->getCollection()
                    ->addFieldToFilter('salesforce_table', 'Product2')
                    ->addFieldToFilter('magento_id', $this->getProductId())
                    ->getFirstItem();
        return $log->getData('datetime') ? $this->formatDate($log->getData('datetime'), \IntlDateFormatter::MEDIUM, true) : 'Never';
    }

    /**
     * Get customer creation date
     *
     * @return string
     */
    public function getLastUpdatedAt()
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', 'Product2')
            ->addFieldToFilter('magento_id', $this->getProductId())
            ->getLastItem();
        return $log->getData('datetime') ? $this->formatDate($log->getData('datetime'), \IntlDateFormatter::MEDIUM, true) : 'Never';
    }

    /**
     * @return string
     */
    public function getSalesforceId($type)
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', $type)
            ->addFieldToFilter('magento_id', $this->getProductId());
        foreach ($log as $v) {
            if ($v->getData('record_id')) {
                return $v->getData('record_id');
            }
        }
        return '';
    }

    public function getSalesforceUrl($type)
    {
        return $this->_scopeConfig->getValue(Connector::XML_PATH_SALESFORCE_INSTANCE_URL).'/'.$this->getSalesforceId($type);
    }

    public function getSyncLog()
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', ['IN'=>['Product2', 'PricebookEntry']])
            ->addFieldToFilter('magento_id', $this->getProductId())
            ->addOrder('datetime', 'DESC')
            ->setPageSize(10)
            ->setCurPage(1);
        return $log;
    }

    public function getSyncButtonUrl()
    {
        return $this->getUrl('salesforce/sync/product', ['id'=>$this->getProductId()]);
    }
}
