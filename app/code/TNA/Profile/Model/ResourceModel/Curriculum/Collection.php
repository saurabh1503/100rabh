<?php


namespace TNA\Profile\Model\ResourceModel\Curriculum;

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
            'TNA\Profile\Model\Curriculum',
            'TNA\Profile\Model\ResourceModel\Curriculum'
        );
    }
}
