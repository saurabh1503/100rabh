<?php
namespace Efloor\Offlinechat\Model\Resource\Grid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection

{

protected $_idFieldName = 'id';

public function __construct(

\Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,

\Psr\Log\LoggerInterface $logger,

\Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,

\Magento\Framework\Event\ManagerInterface $eventManager,

\Magento\Store\Model\StoreManagerInterface $storeManager,

\Magento\Framework\DB\Adapter\AdapterInterface $connection = null,

\Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null

) {

$this->_init(

'Efloor\Offlinechat\Model\Grid',
'Efloor\Offlinechat\Model\Resource\Grid'

);

parent::__construct(

$entityFactory, $logger, $fetchStrategy, $eventManager, $connection,

$resource

);

$this->storeManager = $storeManager;

}

protected function _initSelect()

{

parent::_initSelect();

$this->getSelect()->joinINNER(

['secondTable' => $this->getTable('efloor_requestquote_product')],

'main_table.id = secondTable.requestquote_detail_id',

['product_description']

);

}

}

?>