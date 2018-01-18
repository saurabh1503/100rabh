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

/**
 * Interface FilterInterface
 * @package Mageplaza\LayeredNavigation\Model\Layer\Filter
 */
interface FilterInterface
{
	/**
	 * Apply filter
	 *
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @return mixed
	 */
	public function apply(\Magento\Framework\App\RequestInterface $request);

	/**
	 * Filter type. Used for get item url range or list
	 *
	 * @return mixed
	 */
	public function getFilterType();

	/**
	 * Depend on item is selected or not, return filter url or remove url
	 *
	 * @param $item
	 * @return mixed
	 */
	public function getUrl($item);

	/**
	 * Item is selected or not
	 *
	 * @param $item
	 * @return mixed
	 */
	public function isSelected($item);

	/**
	 * Can show non product options
	 *
	 * @return mixed
	 */
	public function isShowZero();

	/**
	 * Can show counter after option label
	 *
	 * @return mixed
	 */
	public function isShowCounter();

	/**
	 * Is multiple filter
	 *
	 * @return mixed
	 */
	public function isMultiple();
}