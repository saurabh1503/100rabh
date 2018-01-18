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

use Magenest\Salesforce\Model\ResourceModel\Field as ResourceField;
use Magenest\Salesforce\Model\ResourceModel\Field\Collection;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * Class Field
 *
 * @package Magenest\Salesforce\Model
 */
class Field extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'map';

    /**
     * @var \Magenest\Salesforce\Model\Connector
     */
    protected $_connector;

    /**
     * @var string
     */
    protected $mage_field;

    /**
     * @var string
     */
    protected $mage_type;

    /**
     * @var string
     */
    protected $sales_type;

    /**
     * @var string
     */
    protected $sales_field;

    /**
     * @param Context       $context
     * @param Registry      $registry
     * @param ResourceField $resource
     * @param Collection    $resourceCollection
     * @param Connector     $connector
     * @param array         $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ResourceField $resource,
        Collection $resourceCollection,
        Connector $connector,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_connector = $connector;
    }

    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\ResourceModel\Field');
    }

    /**
     * @return mixed
     */
    public function getSalesforceFields()
    {

        $salesFields = $this->getSalesforce();
        if ($salesFields) {
            return unserialize($salesFields);
        } else {
            $this->setSalesforceFields($this->sales_type);
            return unserialize($this->sales_field);
        }
    }

    /**
     * @param $table
     * @param bool|false $update
     * @return $this
     */
    public function loadByTable($table, $update = false)
    {
        $this->load($table, 'type');
        if (!$this->getId() || $update) {
            $this->setType($table);
            $this->saveFields($update);
        } else {
            $this->sales_field = unserialize($this->getData('salesforce'));
            $this->mage_type   = $this->getData('magento');
        }

        return $this;
    }

    /**
     * @param $sales_type
     * @return mixed
     */
    public function setSalesforceFields($sales_type)
    {
        $this->sales_field = $this->_connector->getFields($sales_type);
    }

    /**
     * Set Type of field
     *
     * @param $type
     */
    public function setType($type)
    {
        $this->sales_type = $type;
        $table            = $this->getAllTable();
        if (!empty($table[$type])) {
            $this->mage_type = $table[$type];
        }
    }

    /**
     * Map two table of Magento and Salesforce
     *
     * @return array
     */
    public function getAllTable()
    {
        $table = [
                  'Account'  => 'customer',
                  'Contact'  => 'customer',
                  'Campaign' => 'catalogrule',
                  'Lead'     => 'customer',
                  'Product2' => 'product',
                  'Order'    => 'order',
                 ];

        return $table;
    }

    /**
     * Return option table to select in Admin
     *
     * @return array
     */
    public function changeFields()
    {
        $table = $this->getAllTable();
        $data  = ['' => '--- Select Option ---'];
        foreach ($table as $key => $value) {
            $length = strlen($key);
            $subkey = substr($key, ($length - 3), $length);
            if ($subkey == '__c') {
                $data[$key] = substr($key, 0, ($length - 3));
            } elseif ($key == 'Product2') {
                $data[$key] = 'Product';
            } else {
                $data[$key] = $key;
            }
        }

        return $data;
    }

    /**
     * @param bool|false $update
     * @return $this
     */
    public function saveFields($update = false)
    {
        $this->setSalesforceFields($this->sales_type);
        $data = [
                 'type'       => $this->sales_type,
                 'salesforce' => $this->sales_field,
                 'magento'    => $this->mage_type,
                 'status'     => 1,
                ];

        if ($this->getId() && $update) {
            $this->addData($data);
        } else {
            $this->setData($data);
        }

        $this->save();

        return $this;
    }

    /**
     * Get Magento Field
     *
     * @return array
     */
    public function getMagentoFields()
    {
        if (is_null($this->mage_field)) {
            $this->setMagentoFields($this->mage_type);
        }

        return $this->mage_field;
    }

    /**
     * Set field magento to map
     *
     * @param  $table
     * @return array
     */
    public function setMagentoFields($table)
    {

        $m_fields = [];
        switch ($table) {
            case 'customer':
                $m_fields = [
                         'entity_id'       => 'ID',
                         'email'           => 'Email',
                         'created_at'      => 'Created At',
                         'update_at'       => 'Updated At',
                         'is_active'       => 'is Active',
                         'created_in'      => 'Created in',
                         'prefix'          => 'Prefix',
                         'firstname'       => 'First name',
                         'middlename'      => 'Middle Name/Initial',
                         'lastname'        => 'Last name',
                         'taxvat'          => 'Tax/VAT Number',
                         'gender'          => 'Gender',
                         'dob'             => 'Date of Birth',
                         'bill_firstname'  => 'Billing First Name',
                         'bill_middlename' => 'Billing Middle Name',
                         'bill_lastname'   => 'Billing Last Name',
                         'bill_company'    => 'Billing Company',
                         'bill_street'     => 'Billing Street',
                         'bill_city'       => 'Billing City',
                         'bill_region'     => 'Billing State/Province',
                         'bill_country_id' => 'Billing Country',
                         'bill_postcode'   => 'Billing Zip/Postal Code',
                         'bill_telephone'  => 'Billing Telephone',
                         'bill_fax'        => 'Billing Fax',
                         'ship_firstname'  => 'Shipping First Name',
                         'ship_middlename' => 'Shipping Middle Name',
                         'ship_lastname'   => 'Shipping Last Name',
                         'ship_company'    => 'Shipping Company',
                         'ship_street'     => 'Shipping Street',
                         'ship_city'       => 'Shipping City',
                         'ship_region'     => 'Shipping State/Province',
                         'ship_country_id' => 'Shipping Country',
                         'ship_postcode'   => 'Shipping Zip/Postal Code',
                         'ship_telephone'  => 'Shipping Telephone',
                         'ship_fax'        => 'Shipping Fax',
                         'vat_id'          => 'VAT number',
                        ];
                break;

            case 'catalogrule':
                $m_fields = [
                         'rule_id'             => 'Rule Id',
                         'description'         => 'Description',
                         'from_date'           => 'From Date',
                         'to_date'             => 'To Date',
                         'is_active'           => 'Active',
                         'simple_action'       => 'Simple Action(Apply)',
                         'discount_amount'     => 'Discount Amount',
                         'sub_is_enable'       => 'Enable Discount to Subproducts',
                         'sub_simple_action'   => 'Subproducts Simple Action(Apply)',
                         'sub_discount_amount' => 'Subproducts Discount Amount',
                        ];
                break;

            case 'product':
                $m_fields = [
                         'name'                   => 'Name',
                         'description'            => 'Description',
                         'short_description'      => 'Short Description',
                         'sku'                    => 'SKU',
                         'weight'                 => 'Weight',
                         'news_from_date'         => 'Set Product as New from Date',
                         'news_to_date'           => 'Set Product as New to Date',
                         'status'                 => 'Status',
                         'country_of_manufacture' => 'Country of Manufacture',
                         'url_key'                => 'URL Key',
                         'price'                  => 'Price',
                         'special_price'          => 'Special Price',
                         'special_from_date'      => 'Special From Date',
                         'special_to_date'        => 'Special To Date',
                         'stock_stock_id'         => 'Stock Id',
                         'stock_qty'              => 'Qty',
                         'stock_min_qty'          => 'Min Qty',
                         'meta_title'             => 'Meta Title',
                         'meta_keyword'           => 'Meta Keywords',
                         'meta_description'       => 'Meta Description',
                         'tax_class_id'           => 'Tax Class',
                         'image'                  => 'Base Image',
                         'small_image'            => 'Small Image',
                         'thumbnail'              => 'Thumbnail',
                        ];
                break;

            case 'order':
                $m_fields = [
                         'entity_id'                => 'ID',
                         'state'                    => 'State',
                         'status'                   => 'Status',
                         'coupon_code'              => 'Coupon Code',
                         'coupon_rule_name'         => 'Coupon Rule Name',
                         'increment_id'             => 'Increment ID',
                         'created_at'               => 'Created At',
                         'company'                  => 'Company',
                         'customer_firstname'       => 'Customer First Name',
                         'customer_middlename'      => 'Customer Middle Name',
                         'customer_lastname'        => 'Customer Last Name',
                         'bill_firstname'           => 'Billing First Name',
                         'bill_middlename'          => 'Billing Middle Name',
                         'bill_lastname'            => 'Billing Last Name',
                         'bill_company'             => 'Billing Company',
                         'bill_street'              => 'Billing Street',
                         'bill_city'                => 'Billing City',
                         'bill_region'              => 'Billing State/Province',
                         'bill_postalcode'          => 'Billing Zip/Postal Code',
                         'bill_telephone'           => 'Billing Telephone',
                         'bill_country_id'          => 'Billing Country',
                         'ship_firstname'           => 'Shipping First Name',
                         'ship_middlename'          => 'Shipping Middle Name',
                         'ship_lastname'            => 'Shipping Last Name',
                         'ship_company'             => 'Shipping Company',
                         'ship_street'              => 'Shipping Street',
                         'ship_city'                => 'Shipping City',
                         'ship_region'              => 'Shipping State/Province',
                         'ship_postalcode'          => 'Shipping Zip/Postal Code',
                         'ship_country_id'          => 'Shipping Country',
                         'shipping_amount'          => 'Shipping Amount',
                         'shipping_description'     => 'Shipping Description',
                         'order_currency_code'      => 'Currency Code',
                         'total_item_count'         => 'Total Item Count',
                         'store_currency_code'      => 'Store Currency Code',
                         'shipping_discount_amount' => 'Shipping Discount Amount',
                         'discount_description'     => 'Discount Description',
                         'shipping_method'          => 'Shipping Method',
                         'store_name'               => 'Store Name',
                         'discount_amount'          => 'Discount Amount',
                         'tax_amount'               => 'Tax Amount',
                         'subtotal'                 => 'Sub Total',
                         'grand_total'              => 'Grand Total',
                         'remote_ip'                => 'Remote IP',
                        ];
                break;

            case 'invoice':
                $m_fields = [
                         'entity_id'            => 'ID',
                         'state'                => 'State',
                         'increment_id'         => 'Increment ID',
                         'order_id'             => 'Order ID',
                         'created_at'           => 'Created At',
                         'updated_at'           => 'Updated At',
                         'company'              => 'Company',
                         'customer_firstname'   => 'Customer First Name',
                         'customer_middlename'  => 'Customer Middle Name',
                         'customer_lastname'    => 'Customer Last Name',
                         'bill_firstname'       => 'Billing First Name',
                         'bill_middlename'      => 'Billing Middle Name',
                         'bill_lastname'        => 'Billing Last Name',
                         'bill_company'         => 'Billing Company',
                         'bill_street'          => 'Billing Street',
                         'bill_city'            => 'Billing City',
                         'bill_region'          => 'Billing State/Province',
                         'bill_postalcode'      => 'Billing Zip/Postal Code',
                         'bill_telephone'       => 'Billing Telephone',
                         'bill_country_id'      => 'Billing Country',
                         'ship_firstname'       => 'Shipping First Name',
                         'ship_middlename'      => 'Shipping Middle Name',
                         'ship_lastname'        => 'Shipping Last Name',
                         'ship_company'         => 'Shipping Company',
                         'ship_street'          => 'Shipping Street',
                         'ship_city'            => 'Shipping City',
                         'ship_region'          => 'Shipping State/Province',
                         'ship_postalcode'      => 'Shipping Zip/Postal Code',
                         'ship_country_id'      => 'Shipping Country',
                         'shipping_amount'      => 'Shipping Amount',
                         'order_currency_code'  => 'Currency Code',
                         'total_qty'            => 'Total Qty',
                         'store_currency_code'  => 'Store Currency Code',
                         'discount_description' => 'Discount Description',
                         'shipping_method'      => 'Shipping Method',
                         'shipping_incl_tax'    => 'Shipping Tax',
                         'discount_amount'      => 'Discount Amount',
                         'tax_amount'           => 'Tax Amount',
                         'subtotal'             => 'Sub Total',
                         'grand_total'          => 'Grand Total',
                         'remote_ip'            => 'Remote IP',
                        ];
                break;

            default:
                break;
        }

        $this->mage_field = $m_fields;
    }
}
