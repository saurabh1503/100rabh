<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TNA\Profile\Block\Account\Dashboard;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Dashboard Customer Info
 */
class Info extends \Magento\Customer\Block\Account\Dashboard\Info
{
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param \Magento\Customer\Helper\View $helperView
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magento\Customer\Helper\View $helperView,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->_subscriberFactory = $subscriberFactory;
        $this->_helperView = $helperView;
        parent::__construct($context, $data);
    }

    /**
     * Get the full name of a customer
     *
     * @return string full name
     */
    public function getDob()
    {
        $date = new DateTime($startDate);
        return $date->format('F jS, Y');
        return $this->_helperView->getCustomerName();
    }
}
