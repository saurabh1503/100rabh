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
use Magento\Config\Model\Config as ConfigModel;
use Magento\Catalog\Model\ProductFactory as ProductFactory;
use Magento\Catalog\Model\Category;

/**
 * Class Product
 *
 * @package Magenest\Salesforce\Model\Sync
 */
class Product extends Connector
{
    const SALESFORCE_PRODUCT_ATTRIBUTE_CODE = 'salesforce_product_id';
    const SALESFORCE_PRICEBOOKENTRY_ATTRIBUTE_CODE = 'salesforce_pricebookentry_id';
    const XML_TAX_PRODUCT_ID_PATH = 'salesforcecrm/tax/product_id';
    const XML_TAX_PRICEBOOKENTRY_ID_PATH = 'salesforcecrm/tax/pricebookentry_id';
    const XML_SHIPPING_PRODUCT_ID_PATH = 'salesforcecrm/shipping/product_id';
    const XML_SHIPPING_PRICEBOOKENTRY_ID_PATH = 'salesforcecrm/shipping/pricebookentry_id';

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $_category;

    /**
     * @var Job
     */
    protected $_job;

    protected $existedProducts = [];

    protected $createProductIds = [];

    protected $updateProductIds = [];

    protected $existedPricebookEntry = [];

    protected $createPricebookEntryIds = [];

    protected $updatePricebookEntryIds = [];

    protected $dataGetter;

    /**
     * @var ConfigModel
     */
    protected $configModel;

    /**
     * Product constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ResourceModelConfig $resourceConfig
     * @param ReportFactory $reportFactory
     * @param Data $data
     * @param ProductFactory $productFactory
     * @param Category $category
     * @param Job $job
     * @param ConfigModel $configModel
     * @param DataGetter $dataGetter
     * @param QueueFactory $queueFactory
     * @param RequestLogFactory $requestLogFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResourceModelConfig $resourceConfig,
        ReportFactory $reportFactory,
        Data $data,
        ProductFactory $productFactory,
        Category $category,
        Job $job,
        ConfigModel $configModel,
        DataGetter $dataGetter,
        QueueFactory $queueFactory,
        RequestLogFactory $requestLogFactory
    ) {
        parent::__construct($scopeConfig, $resourceConfig, $reportFactory, $queueFactory, $requestLogFactory);
        $this->_productFactory  = $productFactory;
        $this->_category = $category;
        $this->_data     = $data;
        $this->_type     = 'Product2';
        $this->_table    = 'product';
        $this->_job = $job;
        $this->dataGetter = $dataGetter;
        $this->configModel = $configModel;
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
        /** @var \Magento\Catalog\Model\Product $model */
        $model      = $this->_productFactory->create()->load($id);
        $name       = $model->getName();
        $code       = $model->getSku();
        $price      = $model->getPrice();
        $status     = $model->getStatus();
        $categoryId = $model->getCategoryIds();

        $productId = $this->searchRecords($this->_type, 'ProductCode', $code);
        if (!$productId || ($update && $productId)) {
            // 4. Mapping data
            $params = $this->_data->getProduct($model, $this->_type);

            $params += [
                        'Name'        => $name,
                        'ProductCode' => $code,
                        'isActive'    => $status == 1 ? true : false,
                       ];
            if ($productId) {
                $this->updateRecords($this->_type, $productId, $params, $model->getId());
            } else {
                $productId = $this->createRecords($this->_type, $params, $model->getId());
            }
            $this->saveProductAttribute($model, $productId);

            // 5. Add to Pricebook2 table
            $pricebookEntry['Product2Id']   = $productId;
            $pricebookEntry['isActive']     = $params['isActive'];
            $pricebookEntry['Pricebook2Id'] = $this->searchRecords('Pricebook2', 'Name', 'Standard Price Book');
            $pricebookEntry['UnitPrice']    = $price;

            // 6. Add or Update Standard Price
            $pricebookEntryId = $this->searchRecords('PricebookEntry', 'Product2Id', $productId);
            if ($update && $pricebookEntryId) {
                $this->updateRecords('PricebookEntry', $pricebookEntryId, array('UnitPrice' => $price), $model->getId());
            } else {
                $pricebookEntryId = $this->createRecords('PricebookEntry', $pricebookEntry, $model->getId());
            }
            $this->savePriceBookAttribute($model, $pricebookEntryId);
            if ($categoryId == [] || $update) {
                return $productId;
            } else {
                foreach ($categoryId as $key => $value) {
                    $categoryName = $this->_category->load($value)->getName();
                    // 7. Check Category on PriceBook2 table, if not exist then create new
                    $categoryId = $this->searchRecords('Pricebook2', 'Name', $categoryName);
                    if ($categoryId === false) {
                        $params_category = [
                                            'Name'     => $categoryName,
                                            'isActive' => true,
                                           ];
                        $categoryId      = $this->createRecords('Pricebook2', $params_category, 'CATEGORY');
                    }

                    // 8. Add List Price
                    $pricebookEntry['Pricebook2Id'] = $categoryId;
                    $this->createRecords('PricebookEntry', $pricebookEntry, $model->getId());
                }
            }
        }

        return $productId;
    }

    /**
     * Delete Record
     *
     * @param string $sku
     */
    public function delete($sku)
    {
        $productId = $this->searchRecords('Product2', 'ProductCode', $sku);
        $product = $this->_productFactory->create()->loadByAttribute('sku', $sku);
        $magentoId = $product->getId() ? $product->getId() : null;
        if ($productId) {
            $this->deleteRecords('Product2', $productId, $magentoId);
        }
    }

    /**
     * Sync All Customer on Magento to Salesforce
     */
    public function syncAllProduct()
    {
        try {
            $products = $this->_productFactory->create()->getCollection();
            $lastProductId = $products->getLastItem()->getId();
            $count = 0;
            $response = [];
            /** @var \Magento\Catalog\Model\Product $product */
            foreach ($products as $product) {
                $this->addRecord($product->getId());
                $count++;
                if ($count >= 10000 || $product->getId() == $lastProductId) {
                    $response += $this->syncQueue();
                    break;
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
        $createProductResponse = $this->createProducts();
        $this->saveProductAttributes($this->createProductIds, $createProductResponse);
        $updateProductResponse = $this->updateProducts();
        $this->saveProductAttributes($this->updateProductIds, $updateProductResponse);
        $createPriceResponse = $this->createPricebookEntries();
        $this->savePriceBookAttributes($this->createPricebookEntryIds, $createPriceResponse);
        $updatePriceResponse = $this->updatePricebookEntries();
        $this->savePriceBookAttributes($this->updatePricebookEntryIds, $updatePriceResponse);
        $response = $createProductResponse + $updateProductResponse + $createPriceResponse + $updatePriceResponse;
        $this->unsetCreateProductQueue();
        $this->unsetUpdateProductQueue();
        $this->unsetCreatePriceQueue();
        $this->unsetUpdatePriceQueue();
        return $response;
    }

    /**
     * @param int $productId
     */
    public function addRecord($productId)
    {
        $this->addProductRecord($productId);
        $this->addPricebookEntryRecord($productId);
    }

    /**
     * Send request to create products
     */
    protected function createProducts()
    {
        $response = [];
        if (count($this->createProductIds) > 0) {
            $response = $this->sendProductsRequest($this->createProductIds, 'insert');
        }
        return $response;
    }

    /**
     * Send request to update products
     */
    protected function updateProducts()
    {
        $response = [];
        if (count($this->updateProductIds) > 0) {
            $response = $this->sendProductsRequest($this->updateProductIds, 'update');
        }
        return $response;
    }

    /**
     * Send request to create products
     */
    protected function createPricebookEntries()
    {
        $response = [];
        if (count($this->createPricebookEntryIds) > 0) {
            $response = $this->sendPricebookRequest($this->createPricebookEntryIds, 'insert');
        }
        return $response;
    }

    /**
     * Send request to update products
     */
    protected function updatePricebookEntries()
    {
        $response = [];
        if (count($this->updatePricebookEntryIds) > 0) {
            $response = $this->sendPricebookRequest($this->updatePricebookEntryIds, 'update');
        }
        return $response;
    }

    /**
     * @param $productId
     */
    public function addProductRecord($productId)
    {
        $id = $this->checkExistedProduct($productId);
        if (!$id) {
            $this->addToCreateProductQueue($productId);
        } else {
            $this->addToUpdateProductQueue($id['mid'], $id['sid']);
        }
    }

    /**
     * @param int $productId
     */
    public function addPricebookEntryRecord($productId)
    {
        $id = $this->checkExistedPricebookEntry($productId);
        if (!$id) {
            $this->addToCreatePriceQueue($productId);
        } else {
            $this->addToUpdatePriceQueue($id['mid'], $id['sid']);
        }
    }

    protected function addToCreateProductQueue($productId)
    {
        $this->createProductIds[] = ['mid' => $productId];
    }

    protected function addToUpdateProductQueue($productId, $salesforceId)
    {
        $this->updateProductIds[] = [
            'mid' => $productId,
            'sid' => $salesforceId
        ];
    }

    protected function addToCreatePriceQueue($productId)
    {
        $this->createPricebookEntryIds[] = ['mid' => $productId];
    }

    protected function addToUpdatePriceQueue($productId, $salesforceId)
    {
        $this->updatePricebookEntryIds[] = [
            'mid' => $productId,
            'sid' => $salesforceId
        ];
    }

    protected function unsetCreateProductQueue()
    {
        $this->createProductIds = [];
    }

    protected function unsetUpdateProductQueue()
    {
        $this->updateProductIds = [];
    }

    protected function unsetCreatePriceQueue()
    {
        $this->createPricebookEntryIds = [];
    }

    protected function unsetUpdatePriceQueue()
    {
        $this->updatePricebookEntryIds = [];
    }

    protected function sendProductsRequest($productIds, $operation)
    {
        $params = [];
        foreach ($productIds as $id) {
            $product = $this->_productFactory->create()->load($id['mid']);
            $info = $this->_data->getProduct($product, $this->_type);
            $info += [
                'Name'        => $product->getName(),
                'ProductCode' => $product->getSku(),
                'isActive'    => $product->getStatus() == 1 ? true : false,
            ];
            if (isset($id['sid'])) {
                $info += ['Id' => $id['sid']];
            }
            $params[] = $info;
        }
        $response = $this->_job->sendBatchRequest($operation, $this->_type, json_encode($params));
        $this->saveReports($operation, $this->_type, $response, $productIds);
        return $response;
    }

    protected function sendPricebookRequest($pricebookIds, $operation)
    {
        $params = [];
        $pricebook2Id = $this->searchRecords('Pricebook2', 'Name', 'Standard Price Book');
        foreach ($pricebookIds as $id) {
            $product = $this->_productFactory->create()->load($id['mid']);
            $info = [
                'UnitPrice' => $product->getPrice(),
            ];
            if (isset($id['sid'])) {
                $info += ['Id' => $id['sid']];
            } else {
                $info += [
                    'Product2Id'  => $product->getData(self::SALESFORCE_PRODUCT_ATTRIBUTE_CODE),
                    'Pricebook2Id' => $pricebook2Id,
                    'isActive'    => $product->getStatus() == 1 ? true : false,
                ];
            }
            $params[] = $info;
        }
        $response = $this->_job->sendBatchRequest($operation, 'PricebookEntry', json_encode($params));

        if ($operation == 'insert') {
            $pricebook2Id = $this->searchRecords('Pricebook2', 'Name', 'Standard');
            foreach ($params as &$v) {
                if (isset($v['Pricebook2Id'])) {
                    $v['Pricebook2Id'] = $pricebook2Id;
                }
            }
            $this->_job->sendBatchRequest($operation, 'PricebookEntry', json_encode($params));
        }
        $this->saveReports($operation, 'PricebookEntry', $response, $pricebookIds);
        return $response;
    }


    /**
     * @param int $productId
     * @return array|bool
     */
    protected function checkExistedPricebookEntry($productId)
    {
        $this->existedPricebookEntry = $this->getAllPricebookEntry();
        $product = $this->_productFactory->create()->load($productId);
        foreach ($this->existedPricebookEntry as $key => $pricebookEntry) {
            if (isset($pricebookEntry['ProductCode']) && $product->getSku() == $pricebookEntry['ProductCode']) {
                unset($this->existedPricebookEntry[$key]);
                return [
                    'mid' => $product->getId(),
                    'sid' => $pricebookEntry['Id']
                ];
            }
        }
        return false;
    }

    /**
     * @param int $productId
     * @return array|bool
     */
    protected function checkExistedProduct($productId)
    {
        $this->existedProducts = $this->getAllSalesforceProduct();
        $product = $this->_productFactory->create()->load($productId);
        foreach ($this->existedProducts as $key => $existedProduct) {
            if (isset($existedProduct['ProductCode']) && $product->getSku() == $existedProduct['ProductCode']) {
                unset($this->existedProducts[$key]);
                return [
                    'mid' => $product->getId(),
                    'sid' => $existedProduct['Id']
                ];
            }
        }
        return false;
    }

    /**
     * @return array|mixed|string
     */
    public function getAllSalesforceProduct()
    {
        if (count($this->existedProducts) > 0) {
            return $this->existedProducts;
        }
        $this->existedProducts = $this->dataGetter->getAllSalesforceProducts();
        return $this->existedProducts;
    }

    public function getAllPricebookEntry()
    {
        if (count($this->existedPricebookEntry) > 0) {
            return $this->existedPricebookEntry;
        }
        $this->existedPricebookEntry = $this->dataGetter->getAllPricebookEntry();
        return $this->existedPricebookEntry;
    }

    /**
     * @param $productId
     * @param $response
     * @throws \Exception
     */
    protected function saveProductAttributes($productId, $response)
    {
        if (is_array($response) && is_array($productId)) {
            for ($i=0; $i<count($productId); $i++) {
                $product = $this->_productFactory->create()->load($productId[$i]['mid']);
                if (isset($response[$i]['id']) && $product->getId()) {
                    $this->saveProductAttribute($product, $response[$i]['id']);
                }
            }
        } else {
            throw new \Exception('Response not an array');
        }
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param $salesforceId
     */
    protected function saveProductAttribute($product, $salesforceId)
    {
        $resource = $product->getResource();
        $product->setData(self::SALESFORCE_PRODUCT_ATTRIBUTE_CODE, $salesforceId);
        $resource->saveAttribute($product, self::SALESFORCE_PRODUCT_ATTRIBUTE_CODE);
    }

    /**
     * @param $productId
     * @param $response
     * @throws \Exception
     */
    protected function savePriceBookAttributes($productId, $response)
    {
        if (is_array($response) && is_array($productId)) {
            for ($i=0; $i<count($productId); $i++) {
                $product = $this->_productFactory->create()->load($productId[$i]['mid']);
                if (isset($response[$i]['id']) && $product->getId()) {
                    $this->savePriceBookAttribute($product, $response[$i]['id']);
                }
            }
        } else {
            throw new \Exception('Response not an array');
        }
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param String $salesforceId
     */
    protected function savePriceBookAttribute($product, $salesforceId)
    {
        $resource = $product->getResource();
        $product->setData(self::SALESFORCE_PRICEBOOKENTRY_ATTRIBUTE_CODE, $salesforceId);
        $resource->saveAttribute($product, self::SALESFORCE_PRICEBOOKENTRY_ATTRIBUTE_CODE);
    }

    public function syncTaxProduct()
    {
        $info = [
            'name' => 'Tax',
            'code' => 'TAX',
            'price' => '1'
        ];
        $result = $this->syncAdditionalProduct($info);
        if (isset($result['product_id'])) {
            $this->configModel->setDataByPath(self::XML_TAX_PRODUCT_ID_PATH, $result['product_id']);
            $this->configModel->save();
        } else {
            throw new \Exception('Cant get Tax Product Entry Id');
        }
        if (isset($result['pricebookentry_id'])) {
            $this->configModel->setDataByPath(self::XML_TAX_PRICEBOOKENTRY_ID_PATH, $result['pricebookentry_id']);
            $this->configModel->save();
        } else {
            throw new \Exception('Cant get Tax Pricebook Entry Id');
        }
    }

    public function syncShippingProduct()
    {
        $info = [
            'name' => 'Shipping',
            'code' => 'SHIPPING',
            'price' => '1'
        ];
        $result = $this->syncAdditionalProduct($info);
        if (isset($result['product_id'])) {
            $this->configModel->setDataByPath(self::XML_SHIPPING_PRODUCT_ID_PATH, $result['product_id']);
            $this->configModel->save();
        } else {
            throw new \Exception('Cant get Shipping Product Entry Id');
        }
        if (isset($result['pricebookentry_id'])) {
            $this->configModel->setDataByPath(self::XML_SHIPPING_PRICEBOOKENTRY_ID_PATH, $result['pricebookentry_id']);
            $this->configModel->save();
        } else {
            throw new \Exception('Cant get Shipping Pricebook Entry Id');
        }
    }

    public function syncAdditionalProduct($productInfo)
    {
        $name       = $productInfo['name'];
        $code       = $productInfo['code'];
        $price      = $productInfo['price'];

        $productId = $this->searchRecords($this->_type, 'ProductCode', $code);
        if (!$productId) {
            $params = [
                'Name'        => $name,
                'ProductCode' => $code,
                'isActive'    =>true,
            ];
            $productId = $this->createRecords($this->_type, $params);
        }

        // Add to Pricebook2 table
        $pricebookEntry['Product2Id']   = $productId;
        $pricebookEntry['isActive']     = true;
        $pricebookEntry['Pricebook2Id'] = $this->searchRecords('Pricebook2', 'Name', 'Standard Price Book');
        $pricebookEntry['UnitPrice']    = $price;

        // Add or Update Standard Price
        $pricebookEntryId = $this->searchRecords('PricebookEntry', 'Product2Id', $productId);
        if ($pricebookEntryId) {
            $this->updateRecords('PricebookEntry', $pricebookEntryId, array('UnitPrice' => $price));
        } else {
            $pricebookEntryId = $this->createRecords('PricebookEntry', $pricebookEntry);
        }

        $standardPriceBookId = $this->searchRecords('Pricebook2', 'Name', 'Standard');
        $pricebookEntry['Pricebook2Id'] = $standardPriceBookId;
        $this->createRecords('PricebookEntry', $pricebookEntry);

        return ['product_id' => $productId, 'pricebookentry_id' => $pricebookEntryId];
    }
}
