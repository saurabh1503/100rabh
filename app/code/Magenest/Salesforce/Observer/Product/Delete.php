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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magenest\Salesforce\Model\Sync\Product;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Delete
 */
class Delete implements ObserverInterface
{
    /**
 #@+
     * Constants
     */
    const XML_PATH_SYNC_PRODUCT = 'salesforcecrm/sync/product';

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
     * @param Product              $product
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Product $product,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_product     = $product;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Admin delete customer event
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if (!$this->_scopeConfig->isSetFlag(self::XML_PATH_SYNC_PRODUCT, ScopeInterface::SCOPE_STORE)) {
            return;
        }

        /** @var  $product \Magento\Catalog\Model\Product */
        $product = $observer->getEvent()->getProduct();
        $sku     = $product->getSku();
        $this->_product->delete($sku);
    }
}
