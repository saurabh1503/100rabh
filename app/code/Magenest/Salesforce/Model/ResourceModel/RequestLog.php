<?php
namespace Magenest\Salesforce\Model\ResourceModel;

class RequestLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('magenest_salesforce_request', 'id');
    }
}
