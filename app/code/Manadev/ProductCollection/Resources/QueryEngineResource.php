<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Resources;

use Magento\Framework\DB\Select;
use Manadev\Core\Registries\ProductAttributes;
use Manadev\ProductCollection\Contracts\FacetResourceRegistry;
use Manadev\ProductCollection\Contracts\Filter;
use Manadev\ProductCollection\Contracts\FilterResourceRegistry;
use Manadev\ProductCollection\Contracts\QueryEngine;
use Manadev\ProductCollection\Query;
use Manadev\ProductCollection\Registries\FacetResources;
use Manadev\ProductCollection\Registries\FilterResources;
use Manadev\ProductCollection\Contracts\ProductCollection;

class QueryEngineResource implements QueryEngine
{
    /**
     * @var FilterResources
     */
    protected $filterResources;
    /**
     * @var FacetResources
     */
    protected $facetResources;

    public function __construct(FilterResources $filterResources, FacetResources $facetResources)
    {
        $this->filterResources = $filterResources;
        $this->facetResources = $facetResources;
    }

    /**
     * @return FilterResourceRegistry
     */
    public function getFilterResourceRegistry() {
        return $this->filterResources;
    }

    /**
     * @return FacetResourceRegistry
     */
    public function getFacetResourceRegistry() {
        return $this->facetResources;
    }

    public function run(ProductCollection $productCollection) {
        $query = $productCollection->getQuery();

        $select = clone $productCollection->getSelect();

        $this->applyFiltersToSelect($productCollection->getSelect(), $query);

        foreach ($query->getFacets() as $facet) {
            $resource = $this->facetResources->get($facet->getType());

            if ($resource->isPreparationStepNeeded()) {
                $preparationSelect = $this->applyFiltersToSelect(clone $select, $query,
                    $resource->getPreparationFilterCallback($facet));
                $resource->prepare($preparationSelect, $facet);
            }
            else {
                $preparationSelect = null;
            }

            $facetSelect = $this->applyFiltersToSelect(clone $select, $query, $resource->getFilterCallback($facet));
            $facet->setData($resource->count($facetSelect, $facet));
        }
    }

    /**
     * @param Select $select
     * @param Filter $filter
     * @param callable $callback
     * @return false|string
     */
    public function applyFilterToSelectRecursively(Select $select, Filter $filter, $callback = null) {
        if ($callback && !call_user_func($callback, $filter)) {
            return false;
        }

        $resource = $this->filterResources->get($filter->getType());
        return $resource->apply($select, $filter, $callback);
    }

    protected function applyFiltersToSelect(Select $select, Query $query, $callback = null) {
        if ($condition = $this->applyFilterToSelectRecursively($select, $query->getFilters(), $callback)) {
            $select->where($condition);
        }
        $sql = $select->__toString();

        return $select;
    }
}