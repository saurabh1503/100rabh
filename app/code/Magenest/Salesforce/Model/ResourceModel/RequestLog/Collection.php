<?php
namespace Magenest\Salesforce\Model\ResourceModel\RequestLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\RequestLog', 'Magenest\Salesforce\Model\ResourceModel\RequestLog');
    }
}
