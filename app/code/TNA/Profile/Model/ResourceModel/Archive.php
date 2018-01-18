<?php


namespace TNA\Profile\Model\ResourceModel;

class Archive extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tna_archive', 'archive_id');
    }
}
