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

use Magento\Framework\Model\AbstractModel;

/**
 * Class Map
 *
 * @package Magenest\Salesforce\Model
 *
 * @method Map setStatus(int $status)
 */
class Map extends AbstractModel
{
    /**
     * Initialize resources
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\ResourceModel\Map');
    }

    /**
     * Salesforce
     *
     * @return mixed
     */
    public function getSalesforce()
    {
        return $this->getData('salesforce');
    }

    /**
     * Magento
     *
     * @return mixed
     */
    public function getMagento()
    {
        return $this->getData('magento');
    }
}
