<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_LayeredNavigation
 * @copyright   Copyright (c) 2016 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
namespace TNA\Mageplaza\Model;

use Magento\CatalogSearch\Model\Layer\Filter\Decimal as AbstractFilter;
use Mageplaza\LayeredNavigation\Helper\Data as LayerHelper;

/**
 * Layer category filter
 */
class Decimal extends AbstractFilter implements FilterInterface
{
	/**
	 * @type array
	 */
	protected $filterValue = [];

	/**
	 * Apply price range filter
	 *
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function apply(\Magento\Framework\App\RequestInterface $request)
	{
		/**
		 * Filter must be string: $fromPrice-$toPrice
		 */
		$filter = $request->getParam($this->getRequestVar());
		if (!$filter || is_array($filter)) {
			return $this;
		}

		$this->filterValue[] = $filter;
		list($from, $to) = explode('-', $filter);

		$this->getLayer()
			->getProductCollection()
			->addFieldToFilter(
				$this->getAttributeModel()->getAttributeCode(),
				['from' => $from, 'to' => $to]
			);

		$this->getLayer()->getState()->addFilter(
			$this->_createItem($this->renderRangeLabel(empty($from) ? 0 : $from, $to), $filter)
		);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFilterType()
	{
		return LayerHelper::FILTER_TYPE_LIST;
	}

	/**
	 * Get option url. If it has been filtered, return removed url. Else return filter url
	 *
	 * @param $item
	 * @return mixed
	 */
	public function getUrl($item)
	{
		if ($this->isSelected($item)) {
			return $item->getRemoveUrl();
		}

		return $item->getUrl();
	}

	/**
	 * Check it option is selected or not
	 *
	 * @param $item
	 * @return bool
	 */
	public function isSelected($item)
	{
		if (!empty($this->filterValue) && in_array($item->getValue(), $this->filterValue)) {
			return true;
		}

		return false;
	}

	/**
	 * Allow to show zero options
	 *
	 * @return bool
	 */
	public function isShowZero()
	{
		return false;
	}

	/**
	 * Allow to show counter after options
	 *
	 * @return bool
	 */
	public function isShowCounter()
	{
		return true;
	}

	/**
	 * Allow multiple filter
	 *
	 * @return bool
	 */
	public function isMultiple()
	{
		return true;
	}
}
