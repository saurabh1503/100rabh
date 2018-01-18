<?php

namespace TNA\Mageplaza\Model;
use TNA\Mageplaza\Model\ParentFilter\Attribute as ParentFilter;

class Attribute extends ParentFilter implements FilterInterface
{
	/**
	 * @param $field
	 * @return mixed
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	protected function getAttributeValue($field)
	{
		if ($this->hasAttributeModel()) {
			$lnEnable = $this->getAttributeModel()->getData('layer_' . $field);
			if (!is_null($lnEnable) && $lnEnable != 2) {
				return $lnEnable;
			}
		}

		return $this->_moduleHelper->getGeneralConfig($field);
	}

	/**
	 * @return mixed
	 */
	public function isShowZero()
	{
		return $this->_moduleHelper->getGeneralConfig('show_zero');
	}

	/**
	 * @return mixed
	 */
	public function isEnableSearch()
	{
		return $this->getAttributeValue('search_enable');
	}

	/**
	 * @return mixed
	 */
	public function isShowCounter()
	{
		return $this->_moduleHelper->getGeneralConfig('show_counter');
	}

	/**
	 * @return mixed
	 */
	public function isExpand()
	{
		return $this->getAttributeValue('expand');
	}

	/**
	 * @return mixed
	 */
	public function isMultiple()
	{
		return $this->getAttributeValue('allow_multiple');
	}

	/**
	 * @return string
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function getDisplayType()
	{
		$filterTypeResult = \Mageplaza\LayeredNavigationPro\Helper\Data::DISPLAY_TYPE_LIST;
		if ($this->hasAttributeModel()) {
			$attribute  = $this->getAttributeModel();
			$filterType = $attribute->getData('layer_option_display');
			if (!$filterType) {
				$swatchHelper = $this->_moduleHelper->createObject('Magento\Swatches\Helper\Data');
				if ($swatchHelper->isVisualSwatch($attribute) || $swatchHelper->isTextSwatch($attribute)) {
					$filterType = \Mageplaza\LayeredNavigationPro\Helper\Data::DISPLAY_TYPE_SWATCH;
				}
			}

			$filterTypeResult = $filterType ?: $filterTypeResult;
		}

		return $filterTypeResult;
	}
}
