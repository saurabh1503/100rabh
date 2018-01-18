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
namespace Magenest\Salesforce\Observer\Campaign;

use Magenest\Salesforce\Model\Queue;
use Magenest\Salesforce\Model\QueueFactory;
use Magenest\Salesforce\Observer\SyncObserver;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use \Magenest\Salesforce\Model\Sync\Campaign;

/**
 * Class Update
 */
class Update extends SyncObserver
{
    protected $pathEnable = 'salesforcecrm/sync/campaign';
    protected $pathSyncOption = 'salesforcecrm/sync/campaign_mode';

    /**
     * @var \Magenest\Salesforce\Model\Sync\Campaign
     */
    protected $_campaign;

    /**
     * Update constructor.
     * @param QueueFactory $queueFactory
     * @param ScopeConfigInterface $config
     * @param Campaign $campaign
     */
    public function __construct(
        QueueFactory $queueFactory,
        ScopeConfigInterface $config,
        Campaign $campaign
    ) {
        $this->_campaign = $campaign;
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
            $event = $observer->getEvent();
            /** @var \Magento\CatalogRule\Model\Rule $campaign */
            $campaign = $event->getRule();
            if ($this->getConfigValue($this->pathSyncOption) == 1) {
                $this->addToQueue(Queue::TYPE_CAMPAIGN, $campaign->getId());
            } else {
                /** auto sync mode */
                $event = $observer->getEvent();
                /** @var \Magento\CatalogRule\Model\Rule $campaign */
                $campaign = $event->getRule();
                $id       = $campaign->getId();
                $this->_campaign->sync($id);
            }
        }
    }
}
