<?php
namespace Efloor\Requestquote\Model\Resource\Gridproduct;

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

'Efloor\Requestquote\Model\Gridproduct',
'Efloor\Requestquote\Model\Resource\Gridproduct'

);

parent::__construct(

$entityFactory, $logger, $fetchStrategy, $eventManager, $connection,

$resource

);

$this->storeManager = $storeManager;

}

}

?>