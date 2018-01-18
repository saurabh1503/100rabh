<?php
namespace Magenest\Salesforce\Controller\Adminhtml\Request;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magenest\Salesforce\Model\ReportFactory;
use Magenest\Salesforce\Model\RequestLogFactory;
use Magento\Backend\App\Action;

class Report extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magenest\Salesforce\Model\RequestLogFactory
     */
    protected $requestLogFactory;

    /**
     * @var \Magenest\Salesforce\Model\ReportFactory
     */
    protected $logFactory;

    /**
     * Report constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ReportFactory $logFactory
     * @param RequestLogFactory $requestLogFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ReportFactory $logFactory,
        RequestLogFactory $requestLogFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->requestLogFactory = $requestLogFactory;
        $this->logFactory = $logFactory;
    }

    public function execute()
    {
        $startDate = '';
        $endDate = '';
        $params = $this->getRequest()->getParams();
        foreach ($params as $key => $param) {
            if ($key == 'start_date') {
                $startDate = $param;
            }
            if ($key == 'end_date') {
                $endDate = $param;
            }
        }
        $requestLog = $this->getRequestLog($startDate, $endDate);
        $log = $this->mergeLog($this->getLog($startDate, $endDate), $this->getFailedLog($startDate, $endDate));
        $requestLog['items'] = array_merge($log, $requestLog['items']);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultPage->setData($requestLog);
        return $resultPage;
    }

    /**
     * get request log from $startDate to $endDate
     *
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function getRequestLog($startDate, $endDate)
    {
        return $requestLog = $this->requestLogFactory->create()
            ->getCollection()
            ->addFieldToFilter('date', ['gteq' => date('Y-m-d', strtotime($startDate))])
            ->addFieldToFilter('date', ['lteq' => date('Y-m-d', strtotime($endDate))])
            ->toArray();
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function getLog($startDate, $endDate)
    {
        $log = $this->logFactory->create()->getCollection();
        $log->addFieldToFilter('datetime', ['gteq' => date('Y-m-d', strtotime($startDate))])
            ->addFieldToFilter('datetime', ['lteq' => date('Y-m-d', strtotime($endDate))])
            ->getSelect()
            ->columns(['COUNT(id) as count'])
            ->group('salesforce_table');
        return $log->getData();
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function getFailedLog($startDate, $endDate)
    {
        $log = $this->logFactory->create()->getCollection();
        $log->addFieldToFilter('datetime', ['gteq' => date('Y-m-d', strtotime($startDate))])
            ->addFieldToFilter('datetime', ['lteq' => date('Y-m-d', strtotime($endDate))])
            ->getSelect()
            ->columns(['COUNT(id) as count_failed'])
            ->group('salesforce_table')
            ->group('status')
            ->having('status = 2');
        return $log->getData();
    }

    /**
     * @param $logs
     * @param $failedLogs
     * @return mixed
     */
    protected function mergeLog($logs, $failedLogs)
    {
        foreach ($logs as &$log) {
            foreach ($failedLogs as $failedLog) {
                if (isset($log['salesforce_table']) && isset($failedLog['salesforce_table']) && $log['salesforce_table'] == $failedLog['salesforce_table']) {
                    $log = array_merge($log, $failedLog);
                }
            }
        }
        return $logs;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Salesforce::queue');
    }
}
