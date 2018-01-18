<?php
namespace Efloor\Requestquote\Model\Resource\Grid\Grid;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\AggregationInterface;
use Efloor\Requestquote\Model\Resource\Grid\Collection as EmployeeCollection;

 

/**

* Class Collection

* Collection for displaying grid of shops

*/

class Collection extends EmployeeCollection implements SearchResultInterface

{

/**

* Resource initialization

* @return $this

*/

public function __construct(

\Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,

\Psr\Log\LoggerInterface $logger,

\Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,

\Magento\Framework\Event\ManagerInterface $eventManager,

\Magento\Store\Model\StoreManagerInterface $storeManager,

$mainTable,

$eventPrefix,

$eventObject,

$resourceModel,

$model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',

$connection = null,

\Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null

) {

parent::__construct(

$entityFactory,

$logger,

$fetchStrategy,

$eventManager,

$storeManager,

$connection,

$resource

);

$this->_eventPrefix = $eventPrefix;

$this->_eventObject = $eventObject;

$this->_init($model, $resourceModel);

$this->setMainTable($mainTable);

}

 

/**

* @return AggregationInterface

*/

public function getAggregations()

{

return $this->aggregations;

}

 

/**

* @param AggregationInterface $aggregations

*

* @return $this

*/

public function setAggregations($aggregations)

{

$this->aggregations = $aggregations;

}

 

/**

* Get search criteria.

*

* @return \Magento\Framework\Api\SearchCriteriaInterface|null

*/

public function getSearchCriteria()

{

return null;

}

 

/**

* Set search criteria.

*

* @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria

*

* @return $this

* @SuppressWarnings(PHPMD.UnusedFormalParameter)

*/

public function setSearchCriteria(

\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null

) {

return $this;

}

 

/**

* Get total count.

*

* @return int

*/

public function getTotalCount()

{

return $this->getSize();

}

 

/**

* Set total count.

*

* @param int $totalCount

*

* @return $this

* @SuppressWarnings(PHPMD.UnusedFormalParameter)

*/

public function setTotalCount($totalCount)

{

return $this;

}

 

/**

* Set items list.

*

* @param \Magento\Framework\Api\ExtensibleDataInterface[] $items

*

* @return $this

* @SuppressWarnings(PHPMD.UnusedFormalParameter)

*/

public function setItems(array $items = null)

{

return $this;

}

}





//namespace Efloor\Requestquote\Model\Resource\Grid\Grid;
//use Magento\Framework\Api\Search\SearchResultInterface;
//use Magento\Framework\Search\AggregationInterface;
//// your mane table collection
//use Efloor\Grid\Model\Resource\Grid\Collection as GridCollection;
//use Magento\Framework\Data\Collection\EntityFactoryInterface;
//use Psr\Log\LoggerInterface;
//use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
//use Magento\Framework\Event\ManagerInterface;
//use Magento\Store\Model\StoreManagerInterface;
//use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
// 
///**
// * Class Collection
// * Collection for displaying grid
// */
//class Collection  extends GridCollection implements SearchResultInterface
//{
//    /**
//     * Resource initialization
//     * @param EntityFactoryInterface   $entityFactory,
//     * @param LoggerInterface          $logger,
//     * @param FetchStrategyInterface   $fetchStrategy,
//     * @param ManagerInterface         $eventManager,
//     * @param StoreManagerInterface    $storeManager,
//     * @param String                   $mainTable,
//     * @param String                   $eventPrefix,
//     * @param String                   $eventObject,
//     * @param String                   $resourceModel,
//     * @param $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
//     * @param $connection = null,
//     * @param AbstractDb              $resource = null
//     * @return $this
//     */
//    public function __construct(
//        EntityFactoryInterface $entityFactory,
//        LoggerInterface $logger,
//        FetchStrategyInterface $fetchStrategy,
//        ManagerInterface $eventManager,
//        StoreManagerInterface $storeManager,
//        $mainTable,
//        $eventPrefix,
//        $eventObject,
//        $resourceModel,
//        $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
//        $connection = null,
//        AbstractDb $resource = null
//    ) {
//        parent::__construct(
//            $entityFactory,
//            $logger,
//            $fetchStrategy,
//            $eventManager,
//            $storeManager,
//            $connection,
//            $resource
//        );
//        $this->_eventPrefix = $eventPrefix;
//        $this->_eventObject = $eventObject;
//        $this->_init($model, $resourceModel);
//        $this->setMainTable(efloor_requestquote_detail);
//    }
// 
//    /**
//     * @return AggregationInterface
//     */
//    public function getAggregations()
//    {
//        return $this->aggregations;
//    }
// 
//    /**
//     * @param AggregationInterface $aggregations
//     *
//     * @return $this
//     */
//    public function setAggregations($aggregations)
//    {
//        $this->aggregations = $aggregations;
//    }
// 
//    /**
//     * Get search criteria.
//     *
//     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
//     */
//    public function getSearchCriteria()
//    {
//        return null;
//    }
// 
//    /**
//     * Set search criteria.
//     *
//     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
//     *
//     * @return $this
//     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
//     */
//    public function setSearchCriteria(
//        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
//    ) {
//        return $this;
//    }
// 
//    /**
//     * Get total count.
//     *
//     * @return int
//     */
//    public function getTotalCount()
//    {
//        return $this->getSize();
//    }
// 
//    /**
//     * Set total count.
//     *
//     * @param int $totalCount
//     *
//     * @return $this
//     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
//     */
//    public function setTotalCount($totalCount)
//    {
//        return $this;
//    }
// 
//    /**
//     * Set items list.
//     *
//     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
//     *
//     * @return $this
//     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
//     */
//    public function setItems(array $items = null)
//    {
//        return $this;
//    }
//}
