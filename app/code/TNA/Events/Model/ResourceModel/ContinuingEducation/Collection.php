<?php


namespace TNA\Events\Model\ResourceModel\ContinuingEducation;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'TNA\Events\Model\ContinuingEducation',
            'TNA\Events\Model\ResourceModel\ContinuingEducation'
        );
    }
}
