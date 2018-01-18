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

use Magento\CatalogSearch\Model\Layer\Filter\Category as AbstractFilter;
use Mageplaza\LayeredNavigation\Helper\Data as LayerHelper;

/**
 * Layer category filter
 */
class Category extends AbstractFilter implements FilterInterface
{
	/**
	 * @type array
	 */
	protected $filterValue = [];

	/**
	 * Apply category filter to product collection
	 *
	 * @param   \Magento\Framework\App\RequestInterface $request
	 * @return  $this
	 */
	public function apply(\Magento\Framework\App\RequestInterface $request)
	{
		parent::apply($request);

		$attributeValue = $request->getParam($this->_requestVar) ?: $request->getParam('id');
		if (!empty($attributeValue)) {
			$this->filterValue = explode(',', $attributeValue);
		}

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
		return false;
	}
}
