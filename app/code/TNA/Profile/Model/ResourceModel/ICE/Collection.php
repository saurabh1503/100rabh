<?php


namespace TNA\Profile\Model\ResourceModel\ICE;

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
            'TNA\Profile\Model\ICE',
            'TNA\Profile\Model\ResourceModel\ICE'
        );
    }
}
