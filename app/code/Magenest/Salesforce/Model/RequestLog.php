<?php
namespace Magenest\Salesforce\Model;

class RequestLog extends \Magento\Framework\Model\AbstractModel
{
    const REST_REQUEST_TYPE = 'rest';
    const BULK_REQUEST_TYPE = 'bulk';

    protected function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\ResourceModel\RequestLog');
    }

    public function addRequest($type)
    {
        $type = strtolower($type);
        $column = $type.'_request';
        /** @var \Magenest\Salesforce\Model\RequestLog $request */
        $request = $this->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('date', date('Y-m-d'))
            ->getLastItem();
        if (!$request->getId()) {
            $this->setData('date', date('Y-m-d'));
            $this->setData($column, 1);
            $this->save();
        } else {
            $requestCount = $request->getData($column) + 1;
            $request->setData($column, $requestCount);
            $request->save();
        }
    }
}
