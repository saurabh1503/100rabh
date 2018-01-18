<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Resources\Facets\Swatch;

use Magento\Framework\DB\Select;
use Manadev\Core\Exceptions\NotImplemented;
use Manadev\ProductCollection\Configuration;
use Manadev\ProductCollection\Contracts\Facet;
use Manadev\ProductCollection\Contracts\FacetResource;
use Manadev\ProductCollection\Facets\Swatch\StandardFacet;
use Manadev\ProductCollection\Resources\HelperResource;
use Zend_Db_Expr;
use Magento\Store\Model\StoreManagerInterface;
use Manadev\ProductCollection\Factory;
use Magento\Framework\Model\ResourceModel\Db;
use Magento\Eav\Model\Config;

class StandardFacetResource extends FacetResource
{
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var \Magento\Swatches\Helper\Data
     */
    protected $swatchHelper;

    public function __construct(Db\Context $context, Factory $factory,
        StoreManagerInterface $storeManager, Configuration $configuration,
        HelperResource $helperResource, Config $config,
        \Magento\Swatches\Helper\Data $swatchHelper, $resourcePrefix = null)
    {
        parent::__construct($context, $factory, $storeManager, $configuration, $helperResource, $resourcePrefix);
        $this->config = $config;
        $this->swatchHelper = $swatchHelper;
    }

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct() {
        $this->_setMainTable('catalog_product_index_eav');
    }

    /**
     * @param Select $select
     * @param Facet $facet
     * @return mixed
     */
    public function count(Select $select, Facet $facet) {
        /* @var $facet StandardFacet */
        $this->prepareSelect($select, $facet);
        $counts = $this->getConnection()->fetchPairs($select);

        $minimumOptionCount = $facet->getHideWithSingleVisibleItem() ? 2 : 1;
        if (count($counts) < $minimumOptionCount) {
            return false;
        }

        $attribute = $this->config->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $facet->getAttributeId());
        $options = $attribute->getFrontend()->getSelectOptions();
        $selectedOptionIds = $facet->getSelectedOptionIds();

        $optionIds = [];
        foreach ($options as $option) {
            $optionIds[] = $option['value'];
        }
        $swatches = $this->swatchHelper->getSwatchesByOptionsId($optionIds);

        $emptyOptionSortOrder = false;
        foreach ($options as $sortOrder => &$option) {
            if ($option['value'] === '' && $option['label'] === '') {
                $emptyOptionSortOrder = $sortOrder;
                continue;
            }
            $option['count'] = isset($counts[$option['value']]) ? $counts[$option['value']] : 0;
            $option['is_selected'] = $selectedOptionIds !== false ? in_array($option['value'], $selectedOptionIds) : false;
            $option['sort_order'] = $sortOrder;
            if (isset($swatches[$option['value']])) {
                $option['swatch_type'] = $swatches[$option['value']]['type'];
                $option['swatch'] = $swatches[$option['value']]['value'];
            }
            else {
                $option['swatch_type'] = '0';
                $option['swatch'] = $option['label'];
            }
        }

        if ($emptyOptionSortOrder !== false) {
            unset($options[$emptyOptionSortOrder]);
        }

        return $options;
    }

    public function prepareSelect(Select $select, StandardFacet $facet) {
        $this->helperResource->clearFacetSelect($select);

        $db = $this->getConnection();

        $select
            ->joinInner(array('eav' => $this->getTable('catalog_product_index_eav')),
                "`eav`.`entity_id` = `e`.`entity_id` AND
                {$db->quoteInto("`eav`.`attribute_id` = ?", $facet->getAttributeId())} AND
                {$db->quoteInto("`eav`.`store_id` = ?", $this->getStoreId())}",
                array('value' => new Zend_Db_Expr("`eav`.`value`"), 'count' => "COUNT(DISTINCT `eav`.`entity_id`)")
            )
            ->group(['value' => new Zend_Db_Expr("`eav`.`value`")]);

        return $select;
    }

    public function getFilterCallback(Facet $facet) {
        return $this->helperResource->dontApplyFilterNamed($facet->getName());
    }
}