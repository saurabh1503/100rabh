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
namespace Magenest\Salesforce\Model\ResourceModel\Map;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Magenest\Salesforce\Model\ResourceModel\Map
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
    protected $_eventPrefix = 'magenest_salesforce_map_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'map_collection';


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\Map', 'Magenest\Salesforce\Model\ResourceModel\Map');
    }
}
