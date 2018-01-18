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
namespace Magenest\Salesforce\Observer\Product;

use Magenest\Salesforce\Model\Queue;
use Magenest\Salesforce\Model\QueueFactory;
use Magenest\Salesforce\Observer\SyncObserver;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magenest\Salesforce\Model\Sync\Product;

/**
 * Class Update
 */
class Update extends SyncObserver
{
    protected $pathEnable = 'salesforcecrm/sync/product';
    protected $pathSyncOption = 'salesforcecrm/sync/product_mode';

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Product
     */
    protected $_product;


    /**
     * Update constructor.
     * @param QueueFactory $queueFactory
     * @param Product $product
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        QueueFactory $queueFactory,
        Product $product,
        ScopeConfigInterface $config
    ) {
        $this->_product     = $product;
        parent::__construct($queueFactory, $config);
    }

    /**
     * Admin save a Product
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->getConfigValue($this->pathEnable)) {
            $event = $observer->getEvent();
            /** @var  $product \Magento\Catalog\Model\Product */
            $product = $event->getProduct();
            if ($this->getConfigValue($this->pathSyncOption) == 1) {
                $this->addToQueue(Queue::TYPE_PRODUCT, $product->getId());
            } else {
                $id      = $product->getId();
                $this->_product->sync($id, true);
            }
        }
    }
}
