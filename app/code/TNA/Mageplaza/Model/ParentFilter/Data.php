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
namespace TNA\Mageplaza\Model\ParentFilter;

use Mageplaza\Core\Helper\AbstractData;

/**
 * Class Data
 * @package Mageplaza\LayeredNavigation\Helper
 */
class Data extends AbstractData
{

	const FILTER_TYPE_RANGE = 'range';
	const FILTER_TYPE_LIST = 'list';

	/**
	 * @param null $storeId
	 *
	 * @return mixed
	 */
	public function isEnabled($storeId = null)
	{
		return $this->getConfigValue('layered_navigation/general/enable', $storeId) && $this->isModuleOutputEnabled();
	}

	//public function isEnabled($storeId = null)
	//{
	//	return true;;
	//}
	
	/**
	 * @param string $code
	 * @param null $storeId
	 * @return mixed
	 */
	public function getGeneralConfig($code = '', $storeId = null)
	{
		$code = ($code !== '') ? '/' . $code : '';

		return $this->getConfigValue('layered_navigation/general' . $code, $storeId);
	}

	/**
	 * Layered configuration for js widget
	 *
	 * @param $filters
	 * @return mixed
	 */
	public function getLayerConfiguration($filters)
	{
		$filterParams = $this->_getRequest()->getParams();
		$config       = new \Magento\Framework\DataObject([
			'active' => array_keys($filterParams),
			'params' => $filterParams
		]);

		$slider = [];
		foreach ($filters as $filter) {
			if ($filter->getFilterType() == self::FILTER_TYPE_RANGE) {
				$slider[$filter->getRequestVar()] = $filter->getFilterSliderConfig();
			}
		}
		$config->setData('slider', $slider);

		$this->_eventManager->dispatch('layer_navigation_get_filter_configuration', [
			'config'  => $config,
			'filters' => $filters
		]);

		return $this->objectManager->get('Magento\Framework\Json\EncoderInterface')->encode($config->getData());
	}
}
