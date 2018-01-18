<?php
namespace Magenest\Salesforce\Block\Adminhtml\Request;

use Magenest\Salesforce\Model\RequestLog;
use Magento\Sales\Model\Order;

/**
 * Class TodayRequestForm
 * @package Magenest\Salesforce\Block\Adminhtml\Request
 */
class TodayRequestForm extends \Magento\Backend\Block\Widget
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


    /**
     * @return int
     */
    public function getTodayRestRequest()
    {
        return $this->getTodayRequest(RequestLog::REST_REQUEST_TYPE);
    }

    /**
     * @return int
     */
    public function getTodayBulkRequest()
    {
        return $this->getTodayRequest(RequestLog::BULK_REQUEST_TYPE);
    }

    /**
     * @param String $type
     * @return int
     */
    protected function getTodayRequest($type)
    {
        $requestLog = $this->requestLogFactory->create()->getCollection()
            ->addFieldToFilter('date', date('Y-m-d'))
            ->getFirstItem();
        $column = $type.'_request';
        return $requestLog->getData($column);
    }

    public function getTodayAccountRequest()
    {
        return $this->getTodayItemRequest('Account');
    }

    public function getTodayCampaignRequest()
    {
        return $this->getTodayItemRequest('Campaign');
    }

    public function getTodayContactRequest()
    {
        return $this->getTodayItemRequest('Contact');
    }

    public function getTodayLeadRequest()
    {
        return $this->getTodayItemRequest('Lead');
    }

    public function getTodayOrderRequest()
    {
        return $this->getTodayItemRequest('Order');
    }

    public function getTodayProductRequest()
    {
        return $this->getTodayItemRequest('Product2');
    }

    protected function getTodayItemRequest($itemType)
    {
        $log = $this->logFactory->create()->getCollection();
        $log->addFieldToFilter('datetime', ['gteq' => date('Y-m-d')])
            ->getSelect()
            ->columns(['COUNT(id) as count'])
            ->group('salesforce_table')
            ->having('salesforce_table="'.$itemType.'"');
        foreach ($log as $result) {
            return $result->getData('count');
        }
        return false;
    }
}
