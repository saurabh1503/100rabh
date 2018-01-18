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
namespace Magenest\Salesforce\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Field
 *
 * @package Magenest\Salesforce\Model\ResourceModel
 */
class Field extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magenest_salesforce_field', 'id');
    }
}
