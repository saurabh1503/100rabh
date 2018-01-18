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
namespace Magenest\Salesforce\Observer\Customer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;
use Magenest\Salesforce\Model\Sync\Lead;
use Magenest\Salesforce\Model\Sync\Contact;
use \Magento\Store\Model\ScopeInterface;

/**
 * Class Delete
 */
class Delete implements ObserverInterface
{
    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Lead
     */
    protected $_lead;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Contact
     */
    protected $_contact;


    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Lead                 $lead
     * @param Contact              $contact
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Lead $lead,
        Contact $contact
    ) {
        $this->_lead        = $lead;
        $this->_contact     = $contact;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Admin delete a customer
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getEvent()->getCustomer();
        $email    = $customer->getEmail();
        if ($this->_scopeConfig->isSetFlag(Edit::XML_PATH_SYNC_LEAD, ScopeInterface::SCOPE_STORE)) {
            $this->_lead->delete($email);
        }

        if ($this->_scopeConfig->isSetFlag(Edit::XML_PATH_SYNC_CONTACT, ScopeInterface::SCOPE_STORE)) {
            $this->_contact->delete($email);
        }
    }
}
