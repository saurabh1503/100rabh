<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\Salesforce\Block\Adminhtml\OrderEdit\Tab;

use Magenest\Salesforce\Model\Connector;

/**
 * Order history tab
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class View extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Template
     *
     * @var string
     */
    protected $_template = 'tab/view/salesforce_order_info.phtml';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    protected $logFactory;

    /**
     * View constructor.
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
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }

    /**
     * Retrieve order increment id
     *
     * @return string
     */
    public function getOrderIncrementId()
    {
        return $this->getOrder()->getIncrementId();
    }
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Salesforce Integration');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Sync History');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Get customer creation date
     *
     * @return string
     */
    public function getCreatedAt()
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', 'Order')
            ->addFieldToFilter('magento_id', $this->getOrderIncrementId())
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
            ->addFieldToFilter('salesforce_table', 'Order')
            ->addFieldToFilter('magento_id', $this->getOrderIncrementId())
            ->getLastItem();
        return $log->getData('datetime') ? $this->formatDate($log->getData('datetime'), \IntlDateFormatter::MEDIUM, true) : 'Never';
    }

    /**
     * @return string
     */
    public function getSalesforceId()
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', 'Order')
            ->addFieldToFilter('magento_id', $this->getOrderIncrementId());
        foreach ($log as $v) {
            if ($v->getData('record_id')) {
                return $v->getData('record_id');
            }
        }
        return '';
    }

    public function getSalesforceUrl()
    {
        $url = $this->_scopeConfig->getValue(Connector::XML_PATH_SALESFORCE_INSTANCE_URL).'/'.$this->getSalesforceId();
        return $url;
    }

    public function getSyncLog()
    {
        $log = $this->logFactory->create()->getCollection()
            ->addFieldToFilter('salesforce_table', 'Order')
            ->addFieldToFilter('magento_id', $this->getOrderIncrementId())
            ->addOrder('datetime', 'DESC')
            ->setPageSize(10)
            ->setCurPage(1);
        return $log;
    }

    public function getSyncButtonUrl()
    {
        return $this->getUrl('salesforce/sync/order', ['id'=>$this->getOrderIncrementId()]);
    }
}
