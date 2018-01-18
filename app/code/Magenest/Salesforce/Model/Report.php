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
namespace Magenest\Salesforce\Model;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Backend\Model\Auth\Session;
use Magento\Customer\Model\Session as CustomerSession;
use \Magento\Framework\Model\AbstractModel;

class Report extends AbstractModel
{
    /**
     * Core Date
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_coreDate;

    /**
     * Session Admin
     *
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_backendAuthSession;

    /**
     * Session Customer
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param DateTime $coreDate
     * @param Session $backendAuthSession
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTime $coreDate,
        Session $backendAuthSession,
        CustomerSession $customerSession
    ) {
    
        $this->_coreDate = $coreDate;
        $this->_backendAuthSession = $backendAuthSession;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $registry);
    }

    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\ResourceModel\Report');
    }

    /**
     * @param $id
     * @param $action
     * @param $table
     * @param int $status
     * @param null $message
     * @param null $mid
     */
    public function saveReport($id, $action, $table, $status = 1, $message = null, $mid = null)
    {
        $datetime = $this->_coreDate->gmtDate();
        $admin_user = $this->_backendAuthSession->getUser();
        $current_user = $this->_customerSession->getCustomer();
        if ($admin_user) {
            $name = $admin_user->getName();
            $email = $admin_user->getEmail();
        } elseif ($current_user->getName()) {
            $name = $current_user->getName();
            $email = $current_user->getEmail();
        } else {
            $name = "Guest";
            $email = '';
        }

        $data = [
            'record_id' => $id,
            'magento_id' => $mid,
            'action' => $action,
            'salesforce_table' => $table,
            'datetime' => $datetime,
            'username' => $name,
            'email' => $email,
            'status' => $status,
            'msg' => $message
        ];
        $this->setData($data);
        $this->save();
        return;
    }
}
