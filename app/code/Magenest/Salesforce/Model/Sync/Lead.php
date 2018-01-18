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
 * @author   ThaoPV-<thaopw@gmail.com>
 */
namespace Magenest\Salesforce\Model\Sync;

use Magenest\Salesforce\Model\QueueFactory;
use Magenest\Salesforce\Model\RequestLogFactory;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config as ResourceModelConfig;
use Magenest\Salesforce\Model\ReportFactory as ReportFactory;
use Magenest\Salesforce\Model\Connector;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magenest\Salesforce\Model\Data;

class Lead extends Connector
{
    /**
     * @var /Magento/Customer/Model/CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var Job
     */
    protected $_job;

    protected $existedLeads = [];

    protected $createLeadIds = [];

    protected $updateLeadIds = [];

    protected $dataGetter;

    /**
     * Lead constructor.
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
        $this->_type     = 'Lead';
        $this->_table    = 'customer';
        $this->_job = $job;
        $this->dataGetter = $dataGetter;
    }

    /**
     * Update or create new a record
     *
     * @param  int     $id
     * @param  boolean $update
     * @return string|void
     */
    public function sync($id, $update = false)
    {
        $model     = $this->_customerFactory->create()->load($id);
        $email     = $model->getEmail();
        $firstname = $model->getFirstname();
        $lastname  = $model->getLastname();

        $id = $this->searchRecords($this->_type, 'Email', $email);

        if (!$id || ($update && $id)) {
            $params  = $this->_data->getCustomer($model, $this->_type);
            $params += [
                        'FirstName' => $firstname,
                        'LastName'  => $lastname,
                        'Email'     => $email,
                       ];
            if (empty($params['Company'])) {
                $params['Company'] = 'N/A';
            }

            if ($update && $id) {
                $this->updateRecords($this->_type, $id, $params, $model->getId());
            } else {
                $id = $this->createRecords($this->_type, $params, $model->getId());
            }
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
        $leadId = $this->searchRecords('Lead', 'Email', $email);
        if ($leadId) {
            $this->deleteRecords('Lead', $leadId);
        }
    }

    /**
     * Sync by email
     *
     * @param string $email
     */
    public function syncByEmail($email)
    {
        $leadId = $this->searchRecords('Lead', 'Email', $email);
        if (!$leadId) {
            $params = [
                       'Email'    => $email,
                       'LastName' => 'Guest',
                       'Company'  => 'N/A',
                      ];
            $this->createRecords($this->_type, $params);
        }
    }

    /**
     * Sync All Customer on Magento to Salesforce
     */
    public function syncAllLead()
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
        $createResponse = $this->createLeads();
        $updateResponse = $this->updateLeads();
        $response = $createResponse + $updateResponse;
        $this->unsetCreateQueue();
        $this->unsetUpdateQueue();
        return $response;
    }

    /**
     * Send request to create leads
     */
    protected function createLeads()
    {
        $response = [];
        if (count($this->createLeadIds) > 0) {
            $response = $this->sendLeadsRequest($this->createLeadIds, 'insert');
        }
        return $response;
    }

    /**
     * Send request to update leads
     */
    protected function updateLeads()
    {
        $response = [];
        if (count($this->updateLeadIds) > 0) {
            $response = $this->sendLeadsRequest($this->updateLeadIds, 'update');
        }
        return $response;
    }

    /**
     * @param int $customerId
     */
    public function addRecord($customerId)
    {
        $id = $this->checkExistedLead($customerId);
        if (!$id) {
            $this->addToCreateQueue($customerId);
        } else {
            $this->addToUpdateQueue($id['mid'], $id['sid']);
        }
    }

    protected function addToCreateQueue($customerId)
    {
        $this->createLeadIds[] = ['mid' => $customerId];
    }

    protected function addToUpdateQueue($customerId, $salesforceId)
    {
        $this->updateLeadIds[] = [
            'mid' => $customerId,
            'sid' => $salesforceId
        ];
    }

    protected function unsetCreateQueue()
    {
        $this->createLeadIds = [];
    }

    protected function unsetUpdateQueue()
    {
        $this->updateLeadIds = [];
    }

    protected function sendLeadsRequest($leadIds, $operation)
    {
        $params = [];
        foreach ($leadIds as $id) {
            $customer = $this->_customerFactory->create()->load($id['mid']);
            $info = $this->_data->getCustomer($customer, $this->_type);
            $info += [
                'FirstName' => $customer->getFirstname(),
                'LastName'  => $customer->getLastname(),
                'Email'     => $customer->getEmail(),
                'Company'   => 'N/A',
            ];
            if (isset($id['sid'])) {
                $info += ['Id' => $id['sid']];
            }
            $params[] = $info;
        }
        $response = $this->_job->sendBatchRequest($operation, $this->_type, json_encode($params));
        $this->saveReports($operation, $this->_type, $response, $leadIds);
        return $response;
    }

    /**
     * @param int $customerId
     * @return array|bool
     */
    protected function checkExistedLead($customerId)
    {
        $existedLeads = $this->getAllSalesforceLead();
        $customer = $this->_customerFactory->create()->load($customerId);
        foreach ($existedLeads as $key => $existedLead) {
            if (isset($existedLead['Email']) && strtolower($customer->getEmail()) == $existedLead['Email']) {
                return [
                    'mid' => $customer->getId(),
                    'sid' => $existedLead['Id']
                ];
            }
        }
        return false;
    }

    /**
     * return an array of leads on Salesforce
     * @return array|mixed|string
     */
    public function getAllSalesforceLead()
    {
        if (count($this->existedLeads) > 0) {
            return $this->existedLeads;
        }
        $this->existedLeads = $this->dataGetter->getAllSalesforceLeads();
        return $this->existedLeads;
    }
}
