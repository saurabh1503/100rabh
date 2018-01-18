<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation;

use Magento\Framework\UrlInterface;
use Manadev\Core\Exceptions\NotImplemented;

class UrlGenerator
{
    /**
     * @var Engine
     */
    protected $engine;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var UrlSettings
     */
    protected $urlSettings;

    public function __construct(Engine $engine, UrlInterface $urlBuilder, UrlSettings $urlSettings) {
        $this->engine = $engine;
        $this->urlBuilder = $urlBuilder;
        $this->urlSettings = $urlSettings;
    }

    /**
     * @return string
     */
    public function getClearAllUrl() {
        $queryParameters = [];
        foreach ($this->engine->getAppliedFilters() as $engineFilter) {
            $queryParameters[$engineFilter->getFilter()->getData('param_name')] = null;
        }

        return $this->getUrl($queryParameters);
    }

    /**
     * @param EngineFilter $engineFilter
     * @param $item
     * @return string
     */
    public function getAddItemUrl(EngineFilter $engineFilter, $item) {
        $combinedValues = $engineFilter->getAppliedOptions() ?: [];

        if (!in_array($item['value'], $combinedValues)) {
            $combinedValues[] = $item['value'];
        }

        $combinedValues = implode($this->urlSettings->getMultipleValueSeparator(), $combinedValues);

        return $this->getUrl([$engineFilter->getFilter()->getData('param_name') => $combinedValues]);
    }

    public function getMarkRangeUrl(EngineFilter $engineFilter){
        $rangePattern = "__0__-__1__";
        return $this->getUrl([$engineFilter->getFilter()->getData('param_name') => $rangePattern], false);
    }

    public function getMarkAddItemUrl(EngineFilter $engineFilter) {
        return $this->getUrl([$engineFilter->getFilter()->getData('param_name') => "__0__"], false);
    }

    /**
     * @param EngineFilter $engineFilter
     * @param $item
     * @return string
     */
    public function getRemoveItemUrl(EngineFilter $engineFilter, $item = null) {
        $combinedValues = $engineFilter->getAppliedOptions() ?: [];
        if (!is_array($combinedValues)) {
            $combinedValues = [$combinedValues];
        }

        if(is_null($item)) {
            $combinedValues = [];
        }
        elseif (($index = array_search($item['value'], $combinedValues)) !== false) {
            unset($combinedValues[$index]);
        }

        if (!count($combinedValues)) {
            $combinedValues = null;
        }
        else {
            $combinedValues = implode($this->urlSettings->getMultipleValueSeparator(), $combinedValues);
        }

        return $this->getUrl([$engineFilter->getFilter()->getData('param_name') => $combinedValues]);
    }

    protected function getUrl($queryParameters, $escape = true) {
        return $this->urlBuilder->getUrl('*/*/*', [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => $queryParameters,
            '_escape' => $escape,
        ]);
    }

    public function getReplaceItemUrl(EngineFilter $engineFilter, $item) {
        return $this->getUrl([$engineFilter->getFilter()->getData('param_name') => $item['value']]);
    }
}