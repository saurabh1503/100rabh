<?php
namespace Magenest\Salesforce\Model;

class Queue extends \Magento\Framework\Model\AbstractModel
{
    const TYPE_ACCOUNT = 'Account';
    const TYPE_CAMPAIGN = 'Campaign';
    const TYPE_CONTACT = 'Contact';
    const TYPE_LEAD = 'Lead';
    const TYPE_ORDER = 'Order';
    const TYPE_PRODUCT = 'Product';
    const TYPE_SUBSCRIBER = 'Subscriber';

    protected function _construct()
    {
        $this->_init('Magenest\Salesforce\Model\ResourceModel\Queue');
    }

    public function queueExisted($type, $entityId)
    {
        $existedQueue = $this->getCollection()
            ->addFieldToFilter('type', $type)
            ->addFieldToFilter('entity_id', $entityId)
            ->getFirstItem();
        if ($existedQueue->getId()) {
            /** existed in queue */
            $queue = $this->load($existedQueue->getId());
            $queue->setEnqueueTime(time());
            $queue->save();
            return true;
        }
        return false;
    }

    public function enqueue($type, $entityId)
    {
        $data = [
            'type' => $type,
            'entity_id' => $entityId,
            'enqueue_time' => time(),
            'priority' => 1,
        ];
        $this->setData($data);
        $this->save();
    }

    public function getQueueByType($type)
    {
        $queue = $this->getCollection()
            ->addFieldToFilter('type', $type);
        return $queue;
    }
}
