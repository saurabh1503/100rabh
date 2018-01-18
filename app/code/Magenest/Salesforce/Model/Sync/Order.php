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
use Magento\Sales\Model\OrderFactory;

class Order extends Connector
{
    const SALESFORCE_ORDER_ATTRIBUTE_CODE = 'salesforce_order_id';

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Account
     */
    protected $_account;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Contact
     */
    protected $_contact;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Product
     */
    protected $_product;

    /**
     * @var Job
     */
    protected $_job;

    protected $existedOrders = [];

    protected $createOrderIds = [];

    protected $dataGetter;

    /**
     * Order constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ResourceModelConfig $resourceConfig
     * @param ReportFactory $reportFactory
     * @param Data $data
     * @param OrderFactory $orderFactory
     * @param Account $account
     * @param Contact $contact
     * @param Product $product
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
        OrderFactory $orderFactory,
        Account $account,
        Contact $contact,
        Product $product,
        Job $job,
        DataGetter $dataGetter,
        QueueFactory $queueFactory,
        RequestLogFactory $requestLogFactory
    ) {
        parent::__construct($scopeConfig, $resourceConfig, $reportFactory, $queueFactory, $requestLogFactory);
        $this->_orderFactory  = $orderFactory;
        $this->_account = $account;
        $this->_contact = $contact;
        $this->_product = $product;
        $this->_data    = $data;
        $this->_type    = 'Order';
        $this->_table   = 'order';
        $this->_job = $job;
        $this->dataGetter = $dataGetter;
    }

    /**
     * Create new a Order in Salesforce
     *
     * @param  $increment_id
     * @return string|void
     */
    public function sync($increment_id)
    {
        $model      = $this->_orderFactory->create()->loadByIncrementId($increment_id);
        $customerId = $model->getCustomerId();
        $date       = date('Y-m-d', strtotime($model->getCreatedAt()));
        $email      = $model->getCustomerEmail();
        if ($model->getData(self::SALESFORCE_ORDER_ATTRIBUTE_CODE)) {
            return '';
        }
        /*
            * 1. Get accountId, create new if not exist
            * 2. Create new Contacts if not exist
         */
        if ($customerId) {
            $accountId = $this->_account->sync($customerId);
            $this->_contact->sync($customerId);
        } else {
            $accountId = $this->_account->syncByEmail($email);
            $data      = [
                          'Email'     => $email,
                          'FirstName' => $model->getCustomerFirstname(),
                          'LastName'  => $model->getCustomerLastname(),
                         ];
            $this->_contact->syncByEmail($data);
        }

        $params = $this->_data->getOrder($model, $this->_type);

        // Get pricebookId of "Standard Price Book"
        $pricebookId = $this->searchRecords('Pricebook2', 'Name', 'Standard Price Book');

        /*
            * Require Field:
            *
            * 1. AccountId
            * 2. EffectiveDate
            * 3. Status
            * 4. PriceBook2Id
         */
        $params += [
                    'AccountId'     => $accountId,
                    'EffectiveDate' => $date,
                    'Status'        => 'Draft',
                    'Pricebook2Id'  => $pricebookId,
                   ];

        // Create new Order
        $orderId = $this->createRecords($this->_type, $params, $model->getIncrementId());
        $this->saveAttribute($model, $orderId);

        /*
            * Add new record to OrderItem need:
            *
            * 1. productId
            * 2. pricebookEntryId       *
         */
        foreach ($model->getAllItems() as $item) {
            $product_id = $item->getProductId();
            $qty        = $item->getQtyOrdered();
            $price      = $item->getPrice() - $item->getDiscountAmount()/$qty;
            if ($price > 0) {
                // 5. Get productId
                $productId = $this->_product->sync($product_id);

                $pricebookEntryId = $this->searchRecords('PricebookEntry', 'Product2Id', $productId);
                $output           = [
                                     'PricebookEntryId' => $pricebookEntryId,
                                     'OrderId'          => $orderId,
                                     'Quantity'         => $qty,
                                     'UnitPrice'        => $price,
                                    ];

                // 6. Add Record to OrderItem table
                $this->createRecords('OrderItem', $output, $product_id);
            }
        }//end foreach

        if ($taxInfo = $this->getTaxItemInfo($model, $orderId)) {
            $this->createRecords('OrderItem', $taxInfo, 'TAX');
        }

        if ($shippingInfo = $this->getShippingItemInfo($model, $orderId)) {
            $this->createRecords('OrderItem', $shippingInfo, 'SHIPPING');
        }

        return $orderId;
    }

    public function syncAllOrders()
    {
        try {
            $orders = $this->_orderFactory->create()->getCollection();
            $lastOrderId = $orders->getLastItem()->getId();
            $count = 0;
            $response = [];
            /** @var \Magento\Sales\Model\Order $order */
            foreach ($orders as $order) {
                $this->addRecord($order->getIncrementId());
                $count++;
                if ($count >= 10000 || $order->getId() == $lastOrderId) {
                    $response += $this->syncQueue();
                }
            }
            return $response;
        } catch (\Exception $e) {
            \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($e->getMessage());
        }
        return null;
    }

    /**
     * @param string $orderIncrementId
     */
    public function addRecord($orderIncrementId)
    {
        $order = $this->_orderFactory->create()->loadByIncrementId($orderIncrementId);
        if (!$order->getData(self::SALESFORCE_ORDER_ATTRIBUTE_CODE)) {
            $this->addToCreateProductQueue($orderIncrementId);
        }
    }

    public function syncQueue()
    {
        $response = $this->createOrders();
        $this->saveAttributes($this->createOrderIds, $response);
        $response += $this->createOrderItems();
        $this->unsetCreateProductQueue();
        return $response;
    }


    protected function addToCreateProductQueue($orderIncrementId)
    {
        $this->createOrderIds[] = ['mid' => $orderIncrementId];
    }

    protected function unsetCreateProductQueue()
    {
        $this->createOrderIds = [];
    }

    protected function createOrders()
    {
        $params = [];
        $pricebookId = $this->searchRecords('Pricebook2', 'Name', 'Standard Price Book');
        /** @var \Magento\Sales\Model\Order $order */
        foreach ($this->createOrderIds as $id) {
            $order = $this->_orderFactory->create()->loadByIncrementId($id['mid']);
            $customer = $order->getCustomer();
            $date       = date('Y-m-d', strtotime($order->getCreatedAt()));
            $email      = $order->getCustomerEmail();

            /*
                * 1. Get accountId, create new if not exist
                * 2. Create new Contacts if not exist
             */
            if ($customer && $customer->getData(Account::SALESFORCE_ACCOUNT_ATTRIBUTE_CODE)) {
                $accountId = $customer->getData(Account::SALESFORCE_ACCOUNT_ATTRIBUTE_CODE);
            } elseif ($customer && $customer->getId()) {
                $accountId = $this->_account->sync($customer->getId());
                $this->_contact->sync($customer->getId());
            } else {
                $accountId = $this->_account->syncByEmail($email);
                $address = $order->getBillingAddress();
                if (!$address) {
                    $address = $order->getShippingAddress();
                }
                $data      = [
                    'Email'     => $email,
                    'FirstName' => $address->getFirstname(),
                    'LastName'  => $address->getLastname(),
                ];
                $this->_contact->syncByEmail($data);
            }

            $info = $this->_data->getOrder($order, $this->_type);

            /*
                * Require Field:
                *
                * 1. AccountId
                * 2. EffectiveDate
                * 3. Status
                * 4. PriceBook2Id
             */
            $info += [
                'EffectiveDate' => $date,
                'Status'        => 'Draft',
                'Pricebook2Id'  => $pricebookId,
                'AccountId' => $accountId
            ];

            $params[] = $info;
        }
        $response = $this->_job->sendBatchRequest('insert', $this->_type, json_encode($params));
        $this->saveReports('create', $this->_type, $response, $this->createOrderIds);
        return $response;
    }

    protected function createOrderItems()
    {
        $params = [];
        $itemIds = [];
        foreach ($this->createOrderIds as $id) {
            $order  = $this->_orderFactory->create()->loadByIncrementId($id['mid']);
            $orderId = $order->getData(self::SALESFORCE_ORDER_ATTRIBUTE_CODE);
            foreach ($order->getAllItems() as $item) {
                $qty = $item->getQtyOrdered();
                $price = $item->getPrice() - $item->getDiscountAmount()/$qty;
                $pricebookEntryId = $item->getProduct()->getData(Product::SALESFORCE_PRICEBOOKENTRY_ATTRIBUTE_CODE);

                if ($price > 0) {
                    // 5. Get productId
                    $productId = $item->getProduct()->getData(Product::SALESFORCE_PRODUCT_ATTRIBUTE_CODE);
                    if (!$productId) {
                        $productId = $this->_product->sync($item->getProductId());
                    }
                    if ($productId && $orderId) {
                        if (!$pricebookEntryId) {
                            $pricebookEntryId = $this->searchRecords('PricebookEntry', 'Product2Id', $productId);
                        }
                        $info = [
                            'PricebookEntryId' => $pricebookEntryId,
                            'OrderId' => $orderId,
                            'Quantity' => $qty,
                            'UnitPrice' => $price,
                        ];
                        $params[] = $info;
                        $itemIds[] = ['mid' => $item->getProductId()];
                    }
                }
            }
            if ($taxInfo = $this->getTaxItemInfo($order, $orderId)) {
                $params[] = $taxInfo;
                $itemIds[] = ['mid' => 'TAX'];
            }
            if ($shippingInfo = $this->getShippingItemInfo($order, $orderId)) {
                $params[] = $shippingInfo;
                $itemIds[] = ['mid' => 'SHIPPING'];
            }
        }
        $response = $this->_job->sendBatchRequest('insert', 'OrderItem', json_encode($params));
        $this->saveReports('create', 'OrderItem', $response, $itemIds);
        return $response;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @param string $orderId
     * @return array|null
     */
    protected function getTaxItemInfo($order, $orderId)
    {
        $taxAmount = $order->getTaxAmount();
        if ($taxAmount > 0) {
            $info = [
                'PricebookEntryId' => $this->_scopeConfig->getValue(Product::XML_TAX_PRICEBOOKENTRY_ID_PATH),
                'OrderId' => $orderId,
                'Quantity' => 1,
                'UnitPrice' => $taxAmount,
            ];
            return $info;
        }
        return null;
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @param string $orderId
     * @return array|null
     */
    protected function getShippingItemInfo($order, $orderId)
    {
        $shippingAmount = $order->getShippingAmount();
        if ($shippingAmount > 0) {
            $info = [
                'PricebookEntryId' => $this->_scopeConfig->getValue(Product::XML_SHIPPING_PRICEBOOKENTRY_ID_PATH),
                'OrderId' => $orderId,
                'Quantity' => 1,
                'UnitPrice' => $shippingAmount,
            ];
            return $info;
        }
        return null;
    }

    /**
     * @param $orderIds
     * @param $response
     * @throws \Exception
     */
    protected function saveAttributes($orderIds, $response)
    {
        if (is_array($response) && is_array($orderIds)) {
            for ($i=0; $i<count($orderIds); $i++) {
                $order = $this->_orderFactory->create()->loadByIncrementId($orderIds[$i]['mid']);
                if (isset($response[$i]['id']) && $order->getId()) {
                    $this->saveAttribute($order, $response[$i]['id']);
                }
            }
        } else {
            throw new \Exception('Response not an array');
        }
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @param $salesforceId
     */
    protected function saveAttribute($order, $salesforceId)
    {
        $resource = $order->getResource();
        $order->setData(self::SALESFORCE_ORDER_ATTRIBUTE_CODE, $salesforceId);
        $resource->saveAttribute($order, self::SALESFORCE_ORDER_ATTRIBUTE_CODE);
    }
}
