<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation;

use Magento\Catalog\Model\Layer;
use Manadev\LayeredNavigation\Registries\FilterTypes;
use Manadev\ProductCollection\Contracts\ProductCollection;

class Engine {

    /**4
     * @var EngineFilter[]
     */
    protected $filters = [];

    /**
     * @var Factory
     */
    protected $factory;
    /**
     * @var Layer
     */
    protected $layer;
    /**
     * @var Helper
     */
    protected $helper;
    /**
     * @var FilterTypes
     */
    protected $filterTypes;

    public function __construct(Factory $factory, Layer\Resolver $layerResolver, Helper $helper,
        FilterTypes $filterTypes)
    {
        $this->factory = $factory;
        $this->layer = $layerResolver->get();
        $this->helper = $helper;
        $this->filterTypes = $filterTypes;
    }

    /**
     * @return ProductCollection
     */
    public function getProductCollection() {
        return $this->layer->getProductCollection();
    }

    public function setCurrentCategory($id) {
        $this->resetFilters();
        $this->layer->setCurrentCategory($id);
    }

    protected function resetFilters() {
        $this->filters = [];
    }

    public function prepareFiltersToShowIn($position) {
        foreach ($this->helper->getAllFiltersForCurrentStore() as $filter) {
            if (isset($this->filters[$filter->getData('filter_id')])) {
                continue;
            }

            if (!$filter->getData($position)) {
                continue;
            }

            if (!($filterType = $this->filterTypes->get($filter->getData('type')))) {
                continue;
            }

            if (!($template = $filterType->getTemplates()->get($filter->getData('template')))) {
                continue;
            }

            // create an aggregate structure containing all relevant information about the filter
            $engineFilter = $this->factory->createEngineFilter($this, $filter, $filterType, $template);

            // register filter with the engine
            $this->filters[$filter->getData('filter_id')] = $engineFilter;

            // let the filter register its filtering and counting logic with product collection of the current page
            $engineFilter->prepare();
        }

        // the following fix forces product list to show on static block only categories when filter is applied
        if ($state = $this->layer->getState()) {
            if (!$state->getFilters()) {
                foreach ($this->getAppliedFilters() as $filter) {
                    $state->addFilter($this->factory->createMagentoItem());
                    break;
                }
            }

        }
    }

    /**
     * @param $position
     * @return EngineFilter[]
     */
    public function getFiltersToShowIn($position) {
        foreach ($this->filters as $engineFilter) {
            if (!$engineFilter->getFilter()->getData($position)) {
                continue;
            }

            yield $engineFilter;
        }
    }

    /**
     * @return EngineFilter[]
     */
    public function getAppliedFilters() {
        foreach ($this->filters as $engineFilter) {
            if ($engineFilter->getAppliedOptions() === false) {
                continue;
            }

            yield $engineFilter;
        }
    }

    /**
     * @return EngineFilter[]
     */
    public function getFilters() {
        return $this->filters;
    }
}