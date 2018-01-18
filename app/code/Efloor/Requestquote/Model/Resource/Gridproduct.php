<?php
namespace EEfloor\Requestquote\Model\Resource;

class Gridproduct extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb

{

/**

* Define main table

*/

protected function _construct()

{

$this->_init('efloor_requestquote_product', 'id');

}

}



