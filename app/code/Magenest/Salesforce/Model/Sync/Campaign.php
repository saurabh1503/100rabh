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
use Magento\CatalogRule\Model\RuleFactory;

class Campaign extends Connector
{
    /**
 #@+
    * Constants
    */
    const XML_PATH_SYNC_CAMPAIGN = 'salesforcecrm/sync/campaign';

    /**
     * @var \Magento\CatalogRule\Model\RuleFactory
     */
    protected $_ruleFactory;

    /**
     * @var Job
     */
    protected $_job;

    protected $existedCampaigns = [];

    protected $createCampaignIds = [];

    protected $updateCampaignIds = [];

    protected $dataGetter;

    /**
     * Campaign constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ResourceModelConfig $resourceConfig
     * @param ReportFactory $reportFactory
     * @param Data $data
     * @param RuleFactory $ruleFactory
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
        RuleFactory $ruleFactory,
        Job $job,
        DataGetter $dataGetter,
        QueueFactory $queueFactory,
        RequestLogFactory $requestLogFactory
    ) {
        parent::__construct($scopeConfig, $resourceConfig, $reportFactory, $queueFactory, $requestLogFactory);
        $this->_ruleFactory  = $ruleFactory;
        $this->_data  = $data;
        $this->_type  = 'Campaign';
        $this->_table = 'catalogrule';
        $this->_job = $job;
        $this->dataGetter = $dataGetter;
    }

    /**
     * Create new a record
     *
     * @param  int $id
     * @return string
     */
    public function sync($id)
    {

        $model = $this->_ruleFactory->create()->load($id);
        $name  = $model->getName();

        $id      = $this->searchRecords($this->_type, 'Name', trim($name));
        $params  = $this->_data->getCampaign($model, $this->_type);
        $params += ['Name' => $name];

        if (!$id) {
            $id = $this->createRecords($this->_type, $params, $model->getId());
        } else {
            $this->updateRecords($this->_type, $id, $params, $model->getId());
        }

        return $id;
    }

    /**
     * Sync All Campaigns on Magento to Salesforce
     */
    public function syncAllCampaigns()
    {
        try {
            $rules = $this->_ruleFactory->create()->getCollection();
            $lastRuleId = $rules->getLastItem()->getId();
            $count = 0;
            $response = [];
            /** @var \Magento\CatalogRule\Model\Rule $rule */
            foreach ($rules as $rule) {
                $this->addRecord($rule->getId());
                $count++;
                if ($count >= 10000 || $rule->getId() == $lastRuleId) {
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
        $createResponse = $this->createCampaigns();
        $updateResponse = $this->updateCampaigns();
        $response = $createResponse + $updateResponse;
        $this->unsetCreateQueue();
        $this->unsetUpdateQueue();
        return $response;
    }

    /**
     * Send request to create accounts
     */
    protected function createCampaigns()
    {
        $response = [];
        if (count($this->createCampaignIds) > 0) {
            $response = $this->sendCampaignsRequest($this->createCampaignIds, 'insert');
        }
        return $response;
    }

    /**
     * Send request to update accounts
     */
    protected function updateCampaigns()
    {
        $response = [];
        if (count($this->updateCampaignIds) > 0) {
            $response = $this->sendCampaignsRequest($this->updateCampaignIds, 'update');
        }
        return $response;
    }

    /**
     * @param int $ruleId
     */
    public function addRecord($ruleId)
    {
        $id = $this->checkExistedCampaign($ruleId);
        if (!$id) {
            $this->addToCreateQueue($ruleId);
        } else {
            $this->addToUpdateQueue($id['mid'], $id['sid']);
        }
    }

    protected function addToCreateQueue($ruleId)
    {
        $this->createCampaignIds[] = ['mid' => $ruleId];
    }

    protected function addToUpdateQueue($ruleId, $salesforceId)
    {
        $this->updateCampaignIds[] = [
            'mid' => $ruleId,
            'sid' => $salesforceId
        ];
    }

    protected function unsetCreateQueue()
    {
        $this->createCampaignIds = [];
    }

    protected function unsetUpdateQueue()
    {
        $this->updateCampaignIds = [];
    }

    protected function sendCampaignsRequest($campaignIds, $operation)
    {
        $params = [];
        foreach ($campaignIds as $id) {
            $rule = $this->_ruleFactory->create()->load($id['mid']);
            $info  = $this->_data->getCampaign($rule, $this->_type);
            $info += ['Name' => $rule->getName()];
            if (isset($id['sid'])) {
                $info += ['Id' => $id['sid']];
            }
            $params[] = $info;
        }
        $response = $this->_job->sendBatchRequest($operation, $this->_type, json_encode($params));
        $this->saveReports($operation, $this->_type, $response, $campaignIds);
        return $response;
    }

    /**
     * @param int $ruleId
     * @return array|bool
     */
    protected function checkExistedCampaign($ruleId)
    {
        $existedCampaigns = $this->getAllSalesforceCampaigns();
        $rule = $this->_ruleFactory->create()->load($ruleId);
        foreach ($existedCampaigns as $key => $existedCampaign) {
            if (isset($existedCampaign['Name']) && trim($rule->getName()) == $existedCampaign['Name']) {
                return [
                    'mid' => $rule->getId(),
                    'sid' => $existedCampaign['Id']
                ];
            }
        }
        return false;
    }

    /**
     * return an array of Campaigns on Salesforce
     * @return array|mixed|string
     */
    public function getAllSalesforceCampaigns()
    {
        if (count($this->existedCampaigns) > 0) {
            return $this->existedCampaigns;
        }
        $this->existedCampaigns = $this->dataGetter->getAllSalesforceCampaigns();
        return $this->existedCampaigns;
    }
}
