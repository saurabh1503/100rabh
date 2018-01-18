<?php
namespace Magenest\Salesforce\Controller\Adminhtml\Sync;

use Magenest\Salesforce\Model\Sync;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;

/**
 * Class Order
 * @package Magenest\Salesforce\Controller\Adminhtml\Sync
 */
class Order extends Action
{
    /**
     * @var Sync\Order
     */
    protected $_order;

    /**
     * Customer constructor.
     * @param Context $context
     * @param Sync\Order $order
     */
    public function __construct(
        Context $context,
        Sync\Order $order
    ) {
        $this->_order = $order;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        try {
            $orderIncrementId = $this->getRequest()->getParam('id');
            if ($orderIncrementId) {
                $this->_order->sync($orderIncrementId);
                $this->messageManager->addSuccess(
                    __('Order is synced successfully')
                );
            } else {
                $this->messageManager->addNotice(
                    __('No order has been selected')
                );
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __('Something happen during syncing process. Detail: ' . $e->getMessage())
            );
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Salesforce::config_salesforce');
    }
}
