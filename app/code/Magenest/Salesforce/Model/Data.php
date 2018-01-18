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

use Magento\Directory\Model\Country;
use Magento\Tax\Model\ClassModel;

/**
 * Data Model
 *
 * @author Thao Pham <thaophamit@gmail.com>
 */
class Data
{
    /**
     * @var \Magenest\Salesforce\Model\MapFactory
     */
    protected $_mapFactory;

    /**
     * @var \Magenest\Salesforce\Model\Field
     */
    protected $_field;

    /**
     * @var \Magento\Directory\Model\Country
     */
    protected $_country;

    /**
     * @var \Magento\Directory\Model\Country
     */
    protected $_tax;


    /**
     * @param MapFactory $map
     * @param Field      $field
     * @param Country    $country
     * @param ClassModel $tax
     */
    public function __construct(
        MapFactory $map,
        Field $field,
        Country $country,
        ClassModel $tax
    ) {
        $this->_mapFactory = $map;
        $this->_field      = $field;
        $this->_country    = $country;
        $this->_tax        = $tax;
    }

    /**
     * Select mapping
     *
     * @param  string $data
     * @param  string $_type
     * @return array
     */
    public function getMapping($data, $_type)
    {
        $model      = $this->_mapFactory->create();
        $collection = $model->getResourceCollection()
            ->addFieldToFilter('type', $_type)
            ->addFieldToFilter('status', 1);
        $map        = [];
        $result     = [];

        /** @var Map $value */
        foreach ($collection as $key => $value) {
            $salesforce       = $value->getSalesforce();
            $magento          = $value->getMagento();
            $map[$salesforce] = $magento;
        }

        /** @var string $value */
        foreach ($map as $key => $value) {
            if ($data[$value]) {
                $result[$key] = $data[$value];
            }
        }

        return $result;
    }

    /**
     * Get Country Name
     *
     * @param  string $id
     * @return string
     */
    public function getCountryName($id)
    {
        $model = $this->_country->loadByCode($id);

        return $model->getName();
    }

    /**
     * Get all data of Customer
     *
     * @param  \Magento\Customer\Model\Customer $model
     * @param  string                           $_type
     * @return array
     */
    public function getCustomer($model, $_type)
    {
        $this->_field->setType($_type);
        $magento_fields = $this->_field->getMagentoFields();
        $data           = [];
        foreach ($magento_fields as $key => $item) {
            $sub = substr($key, 0, 5);
            if ($sub == 'bill_' && $model->getDefaultBillingAddress()) {
                $value      = substr($key, 5);
                $billing    = $model->getDefaultBillingAddress();
                $data[$key] = $billing->getData($value);
            } elseif ($sub == 'ship_' && $model->getDefaultShippingAddress()) {
                $value      = substr($key, 5);
                $shipping   = $model->getDefaultShippingAddress();
                $data[$key] = $shipping->getData($value);
            } else {
                $data[$key] = $model->getData($key);
            }
        }

        if (!empty($data['bill_country_id'])) {
            $country_id = $data['bill_country_id'];
            $data['bill_country_id'] = $this->getCountryName($country_id);
        }

        if (!empty($data['ship_country_id'])) {
            $country_id = $data['ship_country_id'];
            $data['ship_country_id'] = $this->getCountryName($country_id);
        }

        // Mapping data
        $params = $this->getMapping($data, $_type);

        return $params;
    }

    /**
     * Pass data of CatalogRule to array and return after mapping
     *
     * @param  \Magento\CatalogRule\Model\Rule $model
     * @param  string                          $_type
     * @return array
     */
    public function getCampaign($model, $_type)
    {
        $this->_field->setType($_type);
        $magento_fields = $this->_field->getMagentoFields();
        $data           = [];

        // Pass data of catalog rule price to array
        foreach ($magento_fields as $key => $item) {
            $data[$key] = $model->getData($key);
        }

        $action = [
                   'by_percent' => 'By Percentage of the Original Price',
                   'by_fixed'   => 'By Fixed Amount',
                   'to_percent' => 'To Percentage of the Original Price',
                   'to_fixed'   => 'To Fixed Amount',
                  ];
        if (!empty($data['simple_action'])) {
            foreach ($action as $key => $value) {
                if ($data['simple_action'] == $key) {
                    $data['simple_action'] = $value;
                }
            }
        }

        if (isset($data['sub_is_enable']) && $data['sub_is_enable'] == 1) {
            $data['sub_is_enable'] = 'Yes';
            foreach ($action as $key => $value) {
                if ($data['simple_action'] == $key) {
                    $data['simple_action'] = $value;
                }
            }
        } else {
            $data['sub_is_enable'] = 'No';
        }

        // Mapping data
        $params = $this->getMapping($data, $_type);

        return $params;
    }

    /**
     * Pass data of Order to array and return mapping
     *
     * @param  \Magento\Sales\Model\Order $model
     * @param  string                     $_type
     * @return array
     */
    public function getOrder($model, $_type)
    {
        $this->_field->setType($_type);
        $magento_fields = $this->_field->getMagentoFields();
        $data           = [];

        foreach ($magento_fields as $key => $item) {
            $sub = substr($key, 0, 5);
            if ($sub == 'bill_') {
                $billing    = $model->getBillingAddress();
                $data[$key] = $billing->getData(substr($key, 5));
            } elseif ($sub == 'ship_') {
                $shipping   = $model->getShippingAddress();
//                $data[$key] = $shipping->getData(substr($key, 5));
            } else {
                $data[$key] = $model->getData($key);
            }
        }

        if (!empty($data['bill_country_id'])) {
            $country_id = $data['bill_country_id'];
            $data['bill_country_id'] = $this->getCountryName($country_id);
            ;
        }

        if (!empty($data['ship_country_id'])) {
            $country_id = $data['ship_country_id'];
            $data['ship_country_id'] = $this->getCountryName($country_id);
            ;
        }

        // Mapping data
        $params = $this->getMapping($data, $_type);

        return $params;
    }


    /**
     * Pass data of Product to array and return after mapping
     *
     * @param  \Magento\Catalog\Model\Product $model
     * @param  string                         $_type
     * @return array
     */
    public function getProduct($model, $_type)
    {
        $this->_field->setType($_type);
        $magento_fields = $this->_field->getMagentoFields();
        $data           = [];

        // ..........Pass data of Product to array..........
        foreach ($magento_fields as $key => $item) {
            $sub = substr($key, 0, 5);
            if ($sub == 'stock') {
                /*
                    * @var \Magento\CatalogInventory\Api\Data\StockItemInterface $stockItem
                */
                $stockItem  = $model->getExtensionAttributes()->getStockItem();
                $data[$key] = $stockItem->getData(substr($key, 6));
            } else {
                $data[$key] = $model->getData($key);
            }
        }

        if (!empty($data['country_of_manufacture'])) {
            $country_id = $data['country_of_manufacture'];
            $data['country_of_manufacture'] = $this->getCountryName($country_id);
        }

        if (!empty($data['tax_class_id'])) {
            $tax_id = $data['tax_class_id'];
            if ($tax_id == 0) {
                $data['tax_class_id'] = "None";
            } else {
                $data['tax_class_id'] = $this->_tax->load($tax_id)->getClassName();
            }
        }

        // .............End pass data...............
        // 4. Mapping data
        $params = $this->getMapping($data, $_type);

        return $params;
    }

    /**
     * Pass data of Invoice to array and return after mapping
     *
     * @param  \Magento\Sales\Model\Order\Invoice $model
     * @param  string                             $_type
     * @return array
     */
    public function getInvoice($model, $_type)
    {
        $this->_field->setType($_type);
        $magento_fields = $this->_field->getMagentoFields();
        $data           = [];

        foreach ($magento_fields as $key => $item) {
            $sub = substr($key, 0, 5);
            if ($sub == 'bill_') {
                $billing    = $model->getBillingAddress();
                $data[$key] = $billing->getData(substr($key, 5));
            } elseif ($sub == 'ship_') {
                $shipping   = $model->getShippingAddress();
                $data[$key] = $shipping->getData(substr($key, 5));
            } else {
                $data[$key] = $model->getData($key);
            }
        }

        $data['order_increment_id'] = $model->getOrderIncrementId();
        if (!empty($data['bill_country_id'])) {
            $country_id = $data['bill_country_id'];
            $data['bill_country_id'] = $this->getCountryName($country_id);
            ;
        }

        if (!empty($data['ship_country_id'])) {
            $country_id = $data['ship_country_id'];
            $data['ship_country_id'] = $this->getCountryName($country_id);
        }

        // Mapping data
        $params = $this->getMapping($data, $_type);

        return $params;
    }
}
