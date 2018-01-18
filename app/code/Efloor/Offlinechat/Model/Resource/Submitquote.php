<?php namespace Efloor\Offlinechat\Model\Resource;
//use Magento\Framework\Model\Resource\Db\AbstractDb as AbstractDb;
class Submitquote extends  \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        /* Custom Table Name */
         $this->_init('efloor_requestquote_detail', 'id');
    }
}