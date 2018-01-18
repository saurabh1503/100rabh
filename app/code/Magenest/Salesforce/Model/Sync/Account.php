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
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config as ResourceModelConfig;
use Magenest\Salesforce\Model\ReportFactory as ReportFactory;
use Magenest\Salesforce\Model\Connector;
use Magenest\Salesforce\Model\Data;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Customer;

/**
 * Class Account
 *
 * @package Magenest\Salesforce\Model\Sync
 */
class Account extends Connector
{
    const SALESFORCE_ACCOUNT_ATTRIBUTE_CODE = 'salesforce_account_id';

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var Job
     */
    protected $_job;

    protected $existedAccounts = [];

    protected $createAccountIds = [];

    protected $updateAccountIds = [];

    protected $dataGetter;

    /**
     * Account constructor.
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
        $this->_data     = $data;
        $this->_customerFactory = $customerFactory;
        $this->_type     = 'Account';
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

        $model = $this->_customerFactory->create()->load($id);
        $email = $model->getEmail();
        $id    = $this->searchRecords($this->_type, 'Name', $email);

        if (!$id || ($update && $id)) {
            // Pass data of customer to array
            $params  = $this->_data->getCustomer($model, $this->_type);
            $params += [
                        'Name'          => $email,
                        'AccountNumber' => $model->getId(),
                       ];
            if ($update && $id) {
                $this->updateRecords($this->_type, $id, $params, $model->getId());
            } else {
                $id = $this->createRecords($this->_type, $params, $model->getId());
            }
        }
        $this->saveAttribute($model, $id);
        return $id;
    }

    /**
     * Create new a record by email
     *
     * @param  string $email
     * @return string
     */
    public function syncByEmail($email)
    {
        $id = $this->searchRecords($this->_type, 'Name', $email);
        if (!$id) {
            $params = ['Name' => $email];
            $id     = $this->createRecords($this->_type, $params);
        }

        return $id;
    }

    /**
     * Sync All Customer on Magento to Salesforce
     */
    public function syncAllAccount()
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
            \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($e->getMessage());
        }
        return null;
    }

    public function syncQueue()
    {
        $createResponse = $this->createAccounts();
        $this->saveAttributes($this->createAccountIds, $createResponse);
        $updateResponse = $this->updateAccounts();
        $this->saveAttributes($this->updateAccountIds, $updateResponse);
        $response = $createResponse + $updateResponse;
        $this->unsetCreateQueue();
        $this->unsetUpdateQueue();
        return $response;
    }

    /**
     * Send request to create accounts
     */
    protected function createAccounts()
    {
        $response = [];
        if (count($this->createAccountIds) > 0) {
            $response = $this->sendAccountsRequest($this->createAccountIds, 'insert');
        }
        return $response;
    }

    /**
     * Send request to update accounts
     */
    protected function updateAccounts()
    {
        $response = [];
        if (count($this->updateAccountIds) > 0) {
            $response = $this->sendAccountsRequest($this->updateAccountIds, 'update');
        }
        return $response;
    }

    /**
     * @param int $customerId
     */
    public function addRecord($customerId)
    {
        $id = $this->checkExistedAccount($customerId);
        if (!$id) {
            $this->addToCreateQueue($customerId);
        } else {
            $this->addToUpdateQueue($id['mid'], $id['sid']);
        }
    }

    protected function addToCreateQueue($customerId)
    {
        $this->createAccountIds[] = ['mid' => $customerId];
    }

    protected function addToUpdateQueue($customerId, $salesforceId)
    {
        $this->updateAccountIds[] = [
            'mid' => $customerId,
            'sid' => $salesforceId
        ];
    }

    protected function unsetCreateQueue()
    {
        $this->createAccountIds = [];
    }

    protected function unsetUpdateQueue()
    {
        $this->updateAccountIds = [];
    }

    protected function sendAccountsRequest($accountIds, $operation)
    {
        $params = [];
        foreach ($accountIds as $id) {
            $customer = $this->_customerFactory->create()->load($id['mid']);
            $info = $this->_data->getCustomer($customer, $this->_type);
            $info += [
                'Name'          => $customer->getEmail(),
                'AccountNumber' => $customer->getId(),
            ];
            if (isset($id['sid'])) {
                $info += ['Id' => $id['sid']];
            }
            $params[] = $info;
        }
        $response = $this->_job->sendBatchRequest($operation, $this->_type, json_encode($params));
        $this->saveReports($operation, $this->_type, $response, $accountIds);
        return $response;
    }

    /**
     * @param int $customerId
     * @return array|bool
     */
    protected function checkExistedAccount($customerId)
    {
        $existedAccounts = $this->getAllSalesforceAccount();
        $customer = $this->_customerFactory->create()->load($customerId);
        foreach ($existedAccounts as $key => $existedAccount) {
            if (isset($existedAccount['Name']) && strtolower($customer->getEmail()) == $existedAccount['Name']) {
                return [
                    'mid' => $customer->getId(),
                    'sid' => $existedAccount['Id']
                ];
            }
        }
        return false;
    }

    /**
     * return an array of accounts on Salesforce
     * @return array|mixed|string
     */
    public function getAllSalesforceAccount()
    {
        if (count($this->existedAccounts) > 0) {
            return $this->existedAccounts;
        }
        $this->existedAccounts = $this->dataGetter->getAllSalesforceAccounts();
        return $this->existedAccounts;
    }

    /**
     * @param $customerIds
     * @param $response
     * @throws \Exception
     */
    protected function saveAttributes($customerIds, $response)
    {
        if (is_array($response) && is_array($customerIds)) {
            for ($i=0; $i<count($customerIds); $i++) {
                $customer = $this->_customerFactory->create()->load($customerIds[$i]['mid']);
                if (isset($response[$i]['id']) && $customer->getId()) {
                    $this->saveAttribute($customer, $response[$i]['id']);
                }
            }
        } else {
            throw new \Exception('Response not an array');
        }
    }

    /**
     * @param Customer $customer
     * @param String $salesforceId
     * @throws \Exception
     */
    protected function saveAttribute($customer, $salesforceId)
    {
        $customerData = $customer->getDataModel();
        $customerData->setId($customer->getId());
        $customerData->setCustomAttribute(self::SALESFORCE_ACCOUNT_ATTRIBUTE_CODE, $salesforceId);
        $customer->updateData($customerData);
        /** @var \Magento\Customer\Model\ResourceModel\Customer $customerResource */
        $customerResource = $this->_customerFactory->create()->getResource();
        $customerResource->saveAttribute($customer, self::SALESFORCE_ACCOUNT_ATTRIBUTE_CODE);
    }
}
