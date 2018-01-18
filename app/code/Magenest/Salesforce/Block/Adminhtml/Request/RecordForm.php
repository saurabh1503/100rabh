<?php
namespace Magenest\Salesforce\Block\Adminhtml\Request;

use Magenest\Salesforce\Model\RequestLog;
use Magento\Sales\Model\Order;

/**
 * Class RecordForm
 * @package Magenest\Salesforce\Block\Adminhtml\Request
 */
class RecordForm extends \Magento\Backend\Block\Widget
{
    /**
     * @var \Magenest\Salesforce\Model\ReportFactory
     */
    protected $logFactory;

    /**
     * @var \Magenest\Salesforce\Model\RequestLogFactory
     */
    protected $requestLogFactory;

    protected $configReader;

    protected $highestDay;

    protected $lowestDay;

    /**
     * Form constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magenest\Salesforce\Model\ReportFactory $logFactory,
        \Magenest\Salesforce\Model\RequestLogFactory $requestLogFactory,
        \Magento\Framework\App\DeploymentConfig\Reader $configReader,
        array $data = []
    ) {
        $this->configReader = $configReader;
        $this->requestLogFactory = $requestLogFactory;
        $this->logFactory = $logFactory;
        parent::__construct($context, $data);
    }

    public function getHighestRequestDay()
    {
        if (!$this->highestDay) {
            $this->highestDay = $this->getRecordDay('DESC');
        }
        return $this->highestDay;
    }

    public function getLowestRequestDay()
    {
        if (!$this->lowestDay) {
            $this->lowestDay = $this->getRecordDay('ASC');
        }
        return $this->lowestDay;
    }

    protected function getRecordDay($order)
    {
        $requestLog = $this->requestLogFactory->create()->getCollection()
            ->addOrder('rest_request', $order)
            ->addOrder('bulk_request', $order)
            ->getFirstItem();
        return $requestLog->getData('date');
    }

    public function getHighestRestRequest()
    {
        return $this->getRequestRecord(RequestLog::REST_REQUEST_TYPE, $this->getHighestRequestDay());
    }

    public function getLowestRestRequest()
    {
        return $this->getRequestRecord(RequestLog::REST_REQUEST_TYPE, $this->getLowestRequestDay());
    }

    public function getHighestBulkRequest()
    {
        return $this->getRequestRecord(RequestLog::BULK_REQUEST_TYPE, $this->getHighestRequestDay());
    }

    public function getLowestBulkRequest()
    {
        return $this->getRequestRecord(RequestLog::BULK_REQUEST_TYPE, $this->getLowestRequestDay());
    }

    protected function getRequestRecord($type, $date)
    {
        $type = $type . '_request';
        $requestLog = $this->requestLogFactory->create()->getCollection()
            ->addFieldToFilter('date', $date)
            ->getFirstItem();
        return $requestLog->getData($type);
    }
}
