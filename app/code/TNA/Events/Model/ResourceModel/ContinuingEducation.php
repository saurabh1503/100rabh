<?php


namespace TNA\Events\Model\ResourceModel;

class ContinuingEducation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('tna_continuing_education', 'continuing_education_id');
    }
}
