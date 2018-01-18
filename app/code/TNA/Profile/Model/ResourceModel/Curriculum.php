<?php


namespace TNA\Profile\Model\ResourceModel;

class Curriculum extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tna_curriculum', 'curriculum_id');
    }
}
