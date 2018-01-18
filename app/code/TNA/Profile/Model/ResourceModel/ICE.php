<?php


namespace TNA\Profile\Model\ResourceModel;

class ICE extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('incaseof_emergency', 'id');
    }
}
