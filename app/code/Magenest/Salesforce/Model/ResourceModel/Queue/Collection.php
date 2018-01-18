<?php
namespace Magenest\Salesforce\Model\ResourceModel\Queue;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\Queue', 'Magenest\Salesforce\Model\ResourceModel\Queue');
    }
}
