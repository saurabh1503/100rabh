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
namespace Magenest\Salesforce\Model\ResourceModel\Report;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Magenest\Salesforce\Model\ResourceModel\Report
 */
class Collection extends AbstractCollection
{
    /**
     * ID Field Name
     *
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'magenest_salesforce_report_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'report_collection';


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\Report', 'Magenest\Salesforce\Model\ResourceModel\Report');
    }
}
