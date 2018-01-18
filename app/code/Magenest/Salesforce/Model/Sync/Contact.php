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
namespace Magenest\Salesforce\Model\Sync;

use Magenest\Salesforce\Model\QueueFactory;
use Magenest\Salesforce\Model\RequestLogFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config as ResourceModelConfig;
use Magenest\Salesforce\Model\ReportFactory as ReportFactory;
use Magenest\Salesforce\Model\Connector;
use Magento\Customer\Model\CustomerFactory;
use Magenest\Salesforce\Model\Data;

class Contact extends Connector
{
    const SALESFORCE_CONTACT_ATTRIBUTE = 'salesforce_contact_id';

    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var Job
     */
    protected $_job;

    protected $existedContacts = [];

    protected $createContactIds = [];

    protected $updateContactIds = [];

    protected $dataGetter;

    /**
     * Contact constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ResourceModelConfig $resourceConfig
     * @param ReportFactory $reportFactory
     * @param Data $data
     * @param CustomerFactory $customerFactory
     * @param Job $job
     * @param DataGetter $dataGetter
     * @param QueueFactory $queueFactory
     * @param RequestLogFactory $requestLogFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResourceModelConfig $resourceConfig,
        ReportFactory $reportFactory,
        Data $data,
        CustomerFactory $customerFactory,
        Job $job,
        DataGetter $dataGetter,
        QueueFactory $queueFactory,
        RequestLogFactory $requestLogFactory
    ) {
        parent::__construct($scopeConfig, $resourceConfig, $reportFactory, $queueFactory, $requestLogFactory);
        $this->_customerFactory = $customerFactory;
        $this->_data     = $data;
        $this->_type     = 'Contact';
        $this->_table    = 'customer';
        $this->_job = $job;
        $this->dataGetter = $dataGetter;
    }

    /**
     * Update or create new a record
     *
     * @param  int     $id
     * @param  boolean $update
     * @return string
     */
    public function sync($id, $update = false)
    {
        $model     = $this->_customerFactory->create()->load($id);
        $email     = $model->getEmail();
        $firstname = $model->getFirstname();
        $lastname  = $model->getLastname();
        $id        = $this->searchRecords($this->_type, 'Email', $email);

        if (!$id || ($update && $id)) {
            $params  = $this->_data->getCustomer($model, $this->_type);
            $params += [
                        'FirstName' => $firstname,
                        'LastName'  => $lastname,
                        'Email'     => $email,
                       ];

            if ($update && $id) {
                $this->updateRecords($this->_type, $id, $params, $model->getId());
            } else {
                $id = $this->createRecords($this->_type, $params, $model->getId());
            }
        }

        return $id;
    }

    /**
     * Sync by Email
     *
     * @param  $data
     * @return string
     */
    public function syncByEmail($data)
    {
        $id = $this->searchRecords($this->_type, 'Email', $data['Email']);
        if (!$id) {
            $params = $data;
            $id     = $this->createRecords($this->_type, $params);
        }

        return $id;
    }

    /**
     * Delete Record
     *
     * @param string $email
     */
    public function delete($email)
    {
        $contactId = $this->searchRecords('Contact', 'Email', $email);
        if ($contactId) {
            $this->deleteRecords('Contact', $contactId);
        }
    }

    /**
     * Sync All Customer on Magento to Salesforce
     */
    public function syncAllContact()
    {
        try {
            $customers = $this->_customerFactory->create()->getCollection();
            $lastCustomerId = $customers->getLastItem()->getId();
            $count = 0;
            $response = [];
            /** @var \Magento\Customer\Model\Customer $customer */
            foreach ($customers as $customer) {
                $this->addRecord($customer->getId());
                $count++;
                if ($count >= 10000 || $customer->getId() == $lastCustomerId) {
                    $response += $this->syncQueue();
                }
            }
            return $response;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function syncQueue()
    {
        $createResponse = $this->createContacts();
        $updateResponse = $this->updateContacts();
        $response = $createResponse + $updateResponse;
        $this->unsetCreateQueue();
        $this->unsetUpdateQueue();
        return $response;
    }

    /**
     * Send request to create contacts
     */
    protected function createContacts()
    {
        $response = [];
        if (count($this->createContactIds) > 0) {
            $response = $this->sendContactsRequest($this->createContactIds, 'insert');
        }
        return $response;
    }

    /**
     * Send request to update contacts
     */
    protected function updateContacts()
    {
        $response = [];
        if (count($this->updateContactIds) > 0) {
            $response = $this->sendContactsRequest($this->updateContactIds, 'update');
        }
        return $response;
    }

    /**
     * @param int $customerId
     */
    public function addRecord($customerId)
    {
        $id = $this->checkExistedContact($customerId);
        if (!$id) {
            $this->addToCreateQueue($customerId);
        } else {
            $this->addToUpdateQueue($id['mid'], $id['sid']);
        }
    }

    protected function addToCreateQueue($customerId)
    {
        $this->createContactIds[] = ['mid' => $customerId];
    }

    protected function addToUpdateQueue($customerId, $salesforceId)
    {
        $this->updateContactIds[] = [
            'mid' => $customerId,
            'sid' => $salesforceId
        ];
    }

    protected function unsetCreateQueue()
    {
        $this->createContactIds = [];
    }

    protected function unsetUpdateQueue()
    {
        $this->updateContactIds = [];
    }

    protected function sendContactsRequest($contactIds, $operation)
    {
        $params = [];
        foreach ($contactIds as $id) {
            $customer = $this->_customerFactory->create()->load($id['mid']);
            $info = $this->_data->getCustomer($customer, $this->_type);
            $info += [
                'FirstName' => $customer->getFirstname(),
                'LastName' => $customer->getLastname(),
                'Email' => $customer->getEmail()
            ];
            if (isset($id['sid'])) {
                $info += ['Id' => $id['sid']];
            }
            $params[] = $info;
        }
        $response = $this->_job->sendBatchRequest($operation, $this->_type, json_encode($params));
        $this->saveReports($operation, $this->_type, $response, $contactIds);
        return $response;
    }

    /**
     * @param int $customerId
     * @return array|bool
     */
    protected function checkExistedContact($customerId)
    {
        $existedContacts = $this->getAllSalesforceContact();
        $customer = $this->_customerFactory->create()->load($customerId);
        foreach ($existedContacts as $key => $existedContact) {
            if (isset($existedContact['Email']) && strtolower($customer->getEmail()) == $existedContact['Email']) {
                return [
                    'mid' => $customer->getId(),
                    'sid' => $existedContact['Id']
                ];
            }
        }
        return false;
    }

    /**
     * return an array of contacts on Salesforce
     * @return array|mixed|string
     */
    public function getAllSalesforceContact($renew = false)
    {
        if (count($this->existedContacts) > 0 && !$renew) {
            return $this->existedContacts;
        }
        $this->existedContacts = $this->dataGetter->getAllSalesforceContacts();
        return $this->existedContacts;
    }
}
