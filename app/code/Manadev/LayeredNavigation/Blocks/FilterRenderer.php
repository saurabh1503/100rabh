<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Blocks;

use Magento\Framework\View\Element\Template;
use Manadev\LayeredNavigation\EngineFilter;
use Manadev\LayeredNavigation\UrlGenerator;

class FilterRenderer extends Template
{
    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;
    /**
     * @var \Magento\Catalog\Helper\Data
     */
    protected $catalogHelper;
    /**
     * @var \Magento\Swatches\Helper\Media
     */
    protected $mediaHelper;

    public function __construct(Template\Context $context,
        \Magento\Catalog\Helper\Data $catalogHelper, \Magento\Swatches\Helper\Media $mediaHelper,
        UrlGenerator $urlGenerator,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->urlGenerator = $urlGenerator;
        $this->catalogHelper = $catalogHelper;
        $this->mediaHelper = $mediaHelper;
    }

    public function render(EngineFilter $engineFilter)
    {
        $this->setTemplate($engineFilter->getTemplateFilename());

        $this->assign('data', $engineFilter->getData());
        $this->assign('filter', $engineFilter->getFilter());
        $this->assign('engineFilter', $engineFilter);

        $html = $this->_toHtml();

        $this->assign('data', null);
        $this->assign('filter', null);
        $this->assign('engineFilter', null);

        return $html;
    }

    public function shouldDisplayProductCountOnLayer() {
        return $this->catalogHelper->shouldDisplayProductCountOnLayer();
    }

    /**
     * @param EngineFilter $engineFilter
     * @param $item
     * @return string
     */
    public function getAddItemUrl(EngineFilter $engineFilter, $item) {
        return $this->escapeUrl($this->urlGenerator->getAddItemUrl($engineFilter, $item));
    }

    public function getRemoveItemUrl(EngineFilter $engineFilter, $item = null) {
        return $this->escapeUrl($this->urlGenerator->getRemoveItemUrl($engineFilter, $item));
    }

    public function getReplaceItemUrl($engineFilter, $item) {
        return $this->escapeUrl($this->urlGenerator->getReplaceItemUrl($engineFilter, $item));
    }

    public function escapeItemLabel(EngineFilter $engineFilter, $label) {
        if ($engineFilter->isLabelHtmlEscaped()) {
            return $this->escapeHtml($label);
        }
        else {
            return $label;
        }
    }

    public function getRangeSliderApplyUrl(EngineFilter $engineFilter){
        return $this->urlGenerator->getMarkRangeUrl($engineFilter);
    }

    public function getMultiSelectSliderApplyUrl(EngineFilter $engineFilter) {
        return $this->urlGenerator->getMarkAddItemUrl($engineFilter);
    }

    public function getSwatchData($data) {
        $attributeOptions = [];
        foreach ($data as $item) {
            if ($currentOption = $this->getFilterOption($this->filter->getItems(), $option)) {
                $attributeOptions[$option->getValue()] = $currentOption;
            } elseif ($this->isShowEmptyResults()) {
                $attributeOptions[$option->getValue()] = $this->getUnusedOption($option);
            }
        }

        $attributeOptionIds = array_keys($attributeOptions);
        $swatches = $this->swatchHelper->getSwatchesByOptionsId($attributeOptionIds);

        $data = [
            'attribute_id' => $this->eavAttribute->getId(),
            'attribute_code' => $this->eavAttribute->getAttributeCode(),
            'attribute_label' => $this->eavAttribute->getStoreLabel(),
            'options' => $attributeOptions,
            'swatches' => $swatches,
        ];

        return $data;
    }

    public function getSwatchPath($type, $filename)
    {
        $imagePath = $this->mediaHelper->getSwatchAttributeImage($type, $filename);

        return $imagePath;
    }
}