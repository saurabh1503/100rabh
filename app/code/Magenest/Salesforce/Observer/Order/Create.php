<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_Salesforce extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_Salesforce
 * @author   ThaoPV
 */
namespace Magenest\Salesforce\Observer\Order;

use Magenest\Salesforce\Model\Queue;
use Magenest\Salesforce\Model\QueueFactory;
use Magenest\Salesforce\Observer\SyncObserver;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magenest\Salesforce\Model\Sync\Order;

/**
 * Class Create
 */
class Create extends SyncObserver
{
    protected $pathEnable = 'salesforcecrm/sync/order';
    protected $pathSyncOption = 'salesforcecrm/sync/order_mode';

    /**
     * @var \Magenest\Salesforce\Model\Sync\Order
     */
    protected $_order;

    /**
     * Create constructor.
     * @param QueueFactory $queueFactory
     * @param ScopeConfigInterface $config
     * @param Order $order
     */
    public function __construct(
        QueueFactory $queueFactory,
        ScopeConfigInterface $config,
        Order $order
    ) {
        $this->_order       = $order;
        parent::__construct($queueFactory, $config);
    }

    /**
     * Admin/Cutomer edit information address
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->getConfigValue($this->pathEnable)) {
            /** @var \Magento\Sales\Model\Order $order */
            $order        = $observer->getEvent()->getOrder();
            if ($this->getConfigValue($this->pathSyncOption) == 1) {
                $this->addToQueue(Queue::TYPE_ORDER, $order->getIncrementId());
            } else {
                /** @var \Magento\Sales\Model\Order $order */
                $order        = $observer->getEvent()->getOrder();
                if (!$order->getData(Order::SALESFORCE_ORDER_ATTRIBUTE_CODE)) {
                    $increment_id = $order->getIncrementId();
                    $this->_order->sync($increment_id);
                }
            }
        }
    }
}
