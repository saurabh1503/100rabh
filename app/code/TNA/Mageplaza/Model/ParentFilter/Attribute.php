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

use Magento\CatalogSearch\Model\Layer\Filter\Attribute as AbstractFilter;
use TNA\Mageplaza\Model\ParentFilter\Data as LayerHelper;

/**
 * Class Attribute
 * @package Mageplaza\LayeredNavigation\Model\Layer\Filter
 */
class Attribute extends AbstractFilter implements FilterInterface
{
	/**
	 * @var \Magento\Framework\Filter\StripTags
	 */
	private $tagFilter;

	/**
	 * filterable value
	 *
	 * @type array
	 */
	protected $filterValue;

	/**
	 * @type \Mageplaza\LayeredNavigation\Helper\Data
	 */
	protected $_moduleHelper;

	/**
	 * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Catalog\Model\Layer $layer
	 * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
	 * @param \Magento\Framework\Filter\StripTags $tagFilter
	 * @param \Mageplaza\LayeredNavigation\Helper\Data $moduleHelper
	 * @param array $data
	 */
	public function __construct(
		\Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Model\Layer $layer,
		\Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
		\Magento\Framework\Filter\StripTags $tagFilter,
		LayerHelper $moduleHelper,
		array $data = []
	)
	{
		parent::__construct(
			$filterItemFactory,
			$storeManager,
			$layer,
			$itemDataBuilder,
			$tagFilter,
			$data
		);
		$this->tagFilter     = $tagFilter;
		$this->_moduleHelper = $moduleHelper;
		$this->filterValue   = [];
	}

	/**
	 * Apply attribute option filter to product collection
	 *
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @return $this
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function apply(\Magento\Framework\App\RequestInterface $request)
	{
		$attributeValue = $request->getParam($this->_requestVar);
		
		$productCollection = $this->getLayer()->getProductCollection();

		if(isset($_GET['date_from'])){
			$updatedAtFrom = date("Y-m-d",strtotime($_GET['date_from']));
        	
			$productcollection =
            $productCollection->addAttributeToSelect('*')
                    ->addAttributeToFilter(
                        [
                            //['attribute'=>'type_id','neq'=> 'simple'],
                            ['attribute'=>'event_start_date','gteq'=> $updatedAtFrom], // From Date filter
                        ]
                    );
		}

		if(isset($_GET['date_to'])){
        	$updatedAtTo = date("Y-m-d H:i:s",strtotime($_GET['date_to']));

			$productcollection =
            $productCollection->addAttributeToSelect('*')
                    ->addAttributeToFilter(
                        [
                            ['attribute'=>'event_start_date','lteq'=> $updatedAtTo], // From Date filter
                        ]
                    );
		}

		if ( empty($attributeValue) ) {
			return $this;
		}

		$attributeValue    = explode(',', $attributeValue);
		$this->filterValue = $attributeValue;

		$attribute = $this->getAttributeModel();
		/** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
		
		if (count($attributeValue) > 1) {
			$productCollection->addFieldToFilter($attribute->getAttributeCode(), ['in' => $attributeValue]);
		} else {
			$productCollection->addFieldToFilter($attribute->getAttributeCode(), $attributeValue[0]);
		}
		
		$state = $this->getLayer()->getState();
		foreach ($attributeValue as $value) {
			$label = $this->getOptionText($value);
			$state->addFilter($this->_createItem($label, $value));
		}

		return $this;
	}

	/**
	 * Get data array for building attribute filter items
	 *
	 * @return array
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	protected function _getItemsData()
	{
		/*if (!$this->_moduleHelper->isEnabled()) {echo '22';die;
			return parent::_getItemsData();
		}*/
		
		$attribute = $this->getAttributeModel();

		/** @var \Mageplaza\LayeredNavigation\Model\ResourceModel\Fulltext\Collection $productCollection */
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$objDate = $objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
		$currentdate = $objDate->gmtDate(); 
		 $productCollection = $this->getLayer()
			->getProductCollection();
        //echo $productCollection->getSelect();die;
		if (!empty($this->filterValue)) {
			$productCollectionClone = $productCollection->getCollectionClone();
			$collection             = $productCollectionClone->removeAttributeSearch($attribute->getAttributeCode());
		} else {
			$collection = $productCollection;
		}
		$collection = $collection->addAttributeToFilter('event_start_date', ['gteq' =>$currentdate]);
		$optionsFacetedData = $collection->getFacetedData($attribute->getAttributeCode());

		if (count($optionsFacetedData) === 0
			&& $this->getAttributeIsFilterable($attribute) !== static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS
		) {
			return $this->itemDataBuilder->build();
		}

		$productSize = $collection->getSize();

		$itemData   = [];
		$checkCount = false;

		$options = $attribute->getFrontend()
			->getSelectOptions();
		foreach ($options as $option) {
			if (empty($option['value'])) {
				continue;
			}

			$value = $option['value'];

			$count = isset($optionsFacetedData[$value]['count'])
				? (int)$optionsFacetedData[$value]['count']
				: 0;

			// Check filter type
			if ($this->getAttributeIsFilterable($attribute) === static::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS
				&& (!$this->isOptionReducesResults($count, $productSize) || ($count == 0 && !$this->isShowZero()))
			) {
				continue;
			}

			if ($count > 0) {
				$checkCount = true;
			}

			$itemData[] = [
				'label' => $this->tagFilter->filter($option['label']),
				'value' => $value,
				'count' => $count
			];
			//echo '<pre>';print_r($itemData);
		}

		if ($checkCount) {
			foreach ($itemData as $item) {
				$this->itemDataBuilder->addItemData($item['label'], $item['value'], $item['count']);
			}
		}

		return $this->itemDataBuilder->build();
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
