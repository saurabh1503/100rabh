<?php

namespace TNA\Events\Observer\Sales;

use Magento\Customer\Helper\Address as CustomerAddress;
use Magento\Customer\Model\Address\AbstractAddress;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address;

class OrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface
{
    protected $customerAddressHelper;
    /**
     * @param CustomerAddress $customerAddressHelper
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        CustomerAddress $customerAddressHelper
    ) {
        $this->logger = $logger;
        $this->customerAddressHelper = $customerAddressHelper;
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
        $orderInstance = $observer->getOrder();
        $this->logger->addInfo("[TNA_Events][OrderPlaceAfter] ");




    }
}
