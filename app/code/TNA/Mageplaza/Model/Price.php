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

use Magento\CatalogSearch\Model\Layer\Filter\Price as AbstractFilter;
use Mageplaza\LayeredNavigation\Helper\Data as LayerHelper;

/**
 * Layer price filter based on Search API
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Price extends AbstractFilter
{
	/**
	 * @var \Magento\Catalog\Model\Layer\Filter\DataProvider\Price
	 */
	private $dataProvider;

	/**
	 * @var \Magento\Framework\Pricing\PriceCurrencyInterface
	 */
	private $priceCurrency;

	/**
	 * @type
	 */
	protected $filterValue;

	/**
	 * @type
	 */
	protected $filterType;

	/**
	 * @type \Mageplaza\LayeredNavigation\Helper\Data
	 */
	protected $_moduleHelper;

	/**
	 * @type \Magento\Tax\Helper\Data
	 */
	protected $_taxHelper;

	/**
	 * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Catalog\Model\Layer $layer
	 * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
	 * @param \Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource
	 * @param \Magento\Customer\Model\Session $customerSession
	 * @param \Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm
	 * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
	 * @param \Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory
	 * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory
	 * @param array $data
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function __construct(
		\Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Model\Layer $layer,
		\Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
		\Magento\Catalog\Model\ResourceModel\Layer\Filter\Price $resource,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\Search\Dynamic\Algorithm $priceAlgorithm,
		\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
		\Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory $algorithmFactory,
		\Magento\Catalog\Model\Layer\Filter\DataProvider\PriceFactory $dataProviderFactory,
		\Mageplaza\LayeredNavigation\Helper\Data $moduleHelper,
		\Magento\Tax\Helper\Data $taxHelper,
		array $data = []
	)
	{
		parent::__construct(
			$filterItemFactory,
			$storeManager,
			$layer,
			$itemDataBuilder,
			$resource,
			$customerSession,
			$priceAlgorithm,
			$priceCurrency,
			$algorithmFactory,
			$dataProviderFactory,
			$data
		);

		$this->priceCurrency = $priceCurrency;
		$this->dataProvider  = $dataProviderFactory->create(['layer' => $this->getLayer()]);
		$this->_moduleHelper = $moduleHelper;
		$this->_taxHelper    = $taxHelper;
		$this->filterValue   = [];
	}

	/**
	 * Apply price range filter
	 *
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @return $this
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	public function apply(\Magento\Framework\App\RequestInterface $request)
	{
		if (!$this->_moduleHelper->isEnabled()) {
			return parent::apply($request);
		}
		/**
		 * Filter must be string: $fromPrice-$toPrice
		 */
		$filter = $request->getParam($this->getRequestVar());
		if (!$filter || is_array($filter)) {
			return $this;
		}
		$filterParams = explode(',', $filter);
		$filter       = $this->dataProvider->validateFilter($filterParams[0]);
		if (!$filter) {
			return $this;
		}

		$this->dataProvider->setInterval($filter);
		$priorFilters = $this->dataProvider->getPriorFilters($filterParams);
		if ($priorFilters) {
			$this->dataProvider->setPriorIntervals($priorFilters);
		}

		$this->filterValue = $filter;
		list($from, $to) = $filter;

		$this->getLayer()->getProductCollection()->addFieldToFilter(
			'price',
			['from' => $from, 'to' => $to]
		);

		$this->getLayer()->getState()->addFilter(
			$this->_createItem($this->_renderRangeLabel(empty($from) ? 0 : $from, $to), $filter)
		);

		return $this;
	}

	/**
	 * Prepare text of range label
	 *
	 * @param float|string $fromPrice
	 * @param float|string $toPrice
	 * @return float|\Magento\Framework\Phrase
	 */
	protected function _renderRangeLabel($fromPrice, $toPrice)
	{
		if (!$this->_moduleHelper->isEnabled()) {
			return parent::_renderRangeLabel($fromPrice, $toPrice);
		}
		$formattedFromPrice = $this->priceCurrency->format($fromPrice);
		if ($toPrice === '') {
			return __('%1 and above', $formattedFromPrice);
		} elseif ($fromPrice == $toPrice && $this->dataProvider->getOnePriceIntervalValue()) {
			return $formattedFromPrice;
		} else {
			return __('%1 - %2', $formattedFromPrice, $this->priceCurrency->format($toPrice));
		}
	}

	/**
	 * Get data array for building attribute filter items
	 *
	 * @return array
	 *
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function _getItemsData()
	{
		if (!$this->_moduleHelper->isEnabled() || ($this->getFilterType() != LayerHelper::FILTER_TYPE_RANGE)) {
			return parent::_getItemsData();
		}

		return [[
			'label' => __('Price Slider'),
			'value' => 'slider',
			'count' => 1
		]];
	}

	/**
	 * @return string
	 */
	public function getFilterType()
	{
		return LayerHelper::FILTER_TYPE_RANGE;
	}

	/**
	 * @return array
	 */
	public function getFilterSliderConfig()
	{
		/** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
		$productCollection      = $this->getLayer()->getProductCollection();
		$productCollectionClone = $productCollection->getCollectionClone();
		$collection             = $productCollectionClone
			->removeAttributeSearch(['price.from', 'price.to']);

		$min = $collection->getMinPrice();
		$max = $collection->getMaxPrice();
		list($from, $to) = $this->filterValue ?: [$min, $max];

		$item = $this->getItems()[0];

		$config = [
			"selectedFrom"  => $from,
			"selectedTo"    => $to,
			"minValue"      => $min,
			"maxValue"      => $max,
			"priceFormat"   => $this->_taxHelper->getPriceFormat(),
			"ajaxUrl"       => $item->getUrl()
		];

		return $config;
	}
}
