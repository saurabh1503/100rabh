<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Resources\Filters;

use Magento\Framework\DB\Select;
use Magento\Framework\Search\Adapter\Mysql\TemporaryStorage;
use Manadev\ProductCollection\Contracts\Filter;
use Manadev\ProductCollection\Contracts\FilterResource;
use Manadev\ProductCollection\Filters\SearchFilter;
use Magento\Store\Model\StoreManagerInterface;
use Manadev\ProductCollection\Factory;
use Magento\Framework\Model\ResourceModel\Db;
use Manadev\ProductCollection\Resources\HelperResource;

class SearchFilterResource extends FilterResource
{
    /**
     * @var \Magento\Search\Model\SearchEngine
     */
    protected $searchEngine;

    /**
     * @var \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory
     */
    protected $temporaryStorageFactory;

    public function __construct(Db\Context $context, Factory $factory,
        StoreManagerInterface $storeManager, HelperResource $helperResource,
        \Magento\Search\Model\SearchEngine $searchEngine,
        \Magento\Framework\Search\Adapter\Mysql\TemporaryStorageFactory $temporaryStorageFactory,
        $resourcePrefix = null)
    {
        parent::__construct($context, $factory, $storeManager, $helperResource, $resourcePrefix);
        $this->searchEngine = $searchEngine;
        $this->temporaryStorageFactory = $temporaryStorageFactory;
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct() {
        $this->_setMainTable('catalogsearch_fulltext_scope' . $this->getStoreId());
    }

    /**
     * @param Select $select
     * @param Filter $filter
     * @param $callback
     * @return false|string
     */
    public function apply(Select $select, Filter $filter, $callback) {
        /** @var $filter SearchFilter */

        $requestBuilder = $this->factory->createRequestBuilder();
        $requestBuilder->bindDimension('scope', $this->getStoreId());
        $requestBuilder->bind('search_term', $filter->getText());
        $requestBuilder->setRequestName('quick_search_container');
        $request = $requestBuilder->create();
        $response = $this->searchEngine->search($request);

        $temporaryStorage = $this->temporaryStorageFactory->create();

        $storeDocuments = method_exists($temporaryStorage, 'storeDocuments')
            ? 'storeDocuments'
            : 'storeApiDocuments';
        $table = $temporaryStorage->$storeDocuments(iterator_to_array($response->getIterator()));

        $select->joinInner(['search_result' => $table->getName()],
            'e.entity_id = search_result.' . TemporaryStorage::FIELD_ENTITY_ID, []);
    }
}