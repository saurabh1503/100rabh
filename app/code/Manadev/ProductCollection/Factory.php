<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection;

use Closure;
use Magento\Catalog\Model\Category;
use Magento\Framework\ObjectManagerInterface;
use Manadev\Core\Exceptions\NotImplemented;
use Manadev\ProductCollection\Enums\Operation;

class Factory {
    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    public function __construct(ObjectManagerInterface $objectManager) {
        $this->objectManager = $objectManager;
    }

    /**
     * @return \Manadev\ProductCollection\Query
     */
    public function createQuery() {
        return $this->objectManager->create('Manadev\ProductCollection\Query', ['factory' => $this]);
    }

    /**
     * @param $name
     * @param string $operator
     * @return \Manadev\ProductCollection\Filters\LogicalFilter
     */
    public function createLogicalFilter($name, $operator = Operation::LOGICAL_AND) {
        return $this->objectManager->create('Manadev\ProductCollection\Filters\LogicalFilter', compact('name', 'operator'));
    }

    public function createSearchFilter($name) {
        return $this->objectManager->create('Manadev\ProductCollection\Filters\SearchFilter', compact('name'));
    }
    public function createLayeredCategoryFilter($name, $ids, $operation = Operation::LOGICAL_OR) {
        return $this->objectManager->create('Manadev\ProductCollection\Filters\LayeredFilters\CategoryFilter',
            compact('name', 'ids', 'operation'));
    }

    public function createLayeredDecimalFilter($name, $attributeId, $ranges, $isToRangeInclusive = false, $operation = Operation::LOGICAL_OR) {
        return $this->objectManager->create('Manadev\ProductCollection\Filters\LayeredFilters\DecimalFilter',
            compact('name', 'attributeId', 'ranges', 'isToRangeInclusive', 'operation'));
    }

    public function createLayeredDropdownFilter($name, $attributeId, $optionIds, $operation = Operation::LOGICAL_OR) {
        return $this->objectManager->create('Manadev\ProductCollection\Filters\LayeredFilters\DropdownFilter',
            compact('name', 'attributeId', 'optionIds', 'operation'));
    }

    public function createLayeredPriceFilter($name, $attributeId, $ranges, $operation = Operation::LOGICAL_OR) {
        return $this->objectManager->create('Manadev\ProductCollection\Filters\LayeredFilters\PriceFilter',
            compact('name', 'attributeId', 'ranges', 'operation'));
    }

    public function getMysqlQueryEngine() {
        return $this->objectManager->get('Manadev\ProductCollection\Resources\QueryEngineResource');
    }

    public function createOptimizedDropdownFacet($name, $attributeId, $selectedOptionIds, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Dropdown\OptimizedFacet',
            compact('name', 'attributeId', 'selectedOptionIds', 'hideWithSingleVisibleItem'));
    }

    public function createStandardDropdownFacet($name, $attributeId, $selectedOptionIds, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Dropdown\StandardFacet',
            compact('name', 'attributeId', 'selectedOptionIds', 'hideWithSingleVisibleItem'));
    }

    public function createOptimizedSwatchFacet($name, $attributeId, $selectedOptionIds, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Swatch\OptimizedFacet',
            compact('name', 'attributeId', 'selectedOptionIds', 'hideWithSingleVisibleItem'));
    }

    public function createStandardSwatchFacet($name, $attributeId, $selectedOptionIds, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Swatch\StandardFacet',
            compact('name', 'attributeId', 'selectedOptionIds', 'hideWithSingleVisibleItem'));
    }

    public function createChildCategoryFacet($name, $appliedCategory, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Category\ChildFacet',
            compact('name', 'appliedCategory', 'hideWithSingleVisibleItem'));
    }

    public function createEqualizedRangePriceFacet($name, $appliedRanges, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Price\EqualizedRangeFacet',
            compact('name', 'appliedRanges', 'hideWithSingleVisibleItem'));
    }

    public function createEqualizedCountPriceFacet($name, $appliedRanges, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Price\EqualizedCountFacet',
            compact('name', 'appliedRanges', 'hideWithSingleVisibleItem'));
    }

    public function createManualRangePriceFacet($name, $appliedRanges, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Price\ManualRangeFacet',
            compact('name', 'appliedRanges', 'hideWithSingleVisibleItem'));
    }

    public function createEqualizedRangeDecimalFacet($name, $attributeId, $appliedRanges, $hideWithSingleVisibleItem) {
        return $this->objectManager->create('Manadev\ProductCollection\Facets\Decimal\EqualizedRangeFacet',
            compact('name', 'attributeId', 'appliedRanges', 'hideWithSingleVisibleItem'));
    }

    public function createSliderMinMaxDecimalFacet($name, $attributeId, $appliedRanges, $calculateSliderMinMax, $numberFormat, $showThousandSeparator, $minMaxRole, $minAttributeCode, $precision, $maxFilterId = null){
        return $this->objectManager->create('Manadev\LayeredNavigationSliders\Facets\Decimal\MinMaxSliderRangeFacet',
            compact('name', 'attributeId', 'appliedRanges', 'calculateSliderMinMax', 'numberFormat', 'showThousandSeparator', 'minMaxRole', 'minAttributeCode', 'precision', 'maxFilterId'));
    }

    public function createSliderRangeDecimalFacet($name, $attributeId, $appliedRanges, $calculateSliderMinMax, $numberFormat, $showThousandSeparator, $precision) {
        return $this->objectManager->create('Manadev\LayeredNavigationSliders\Facets\Decimal\SliderRangeFacet',
            compact('name', 'attributeId', 'appliedRanges', 'calculateSliderMinMax', 'numberFormat', 'showThousandSeparator', 'precision'));
    }

    public function createSliderRangePriceFacet($name, $attributeId, $appliedRanges, $calculateSliderMinMax, $numberFormat, $showThousandSeparator, $precision) {
        return $this->objectManager->create('Manadev\LayeredNavigationSliders\Facets\Price\PriceSliderRangeFacet',
            compact('name', 'attributeId', 'appliedRanges', 'calculateSliderMinMax', 'numberFormat', 'showThousandSeparator', 'precision'));
    }

    public function createSliderRangeDropdownFacet($name, $attributeId, $selectedOptionIds, $calculateSliderMinMax) {
        return $this->objectManager->create('Manadev\LayeredNavigationSliders\Facets\Dropdown\DropdownSliderRangeFacet',
            compact('name', 'attributeId', 'selectedOptionIds', 'calculateSliderMinMax'));
    }

    /**
     * @return \Magento\Framework\Search\Request\Builder
     */
    public function createRequestBuilder() {
        return $this->objectManager->create('Magento\Framework\Search\Request\Builder');
    }
}