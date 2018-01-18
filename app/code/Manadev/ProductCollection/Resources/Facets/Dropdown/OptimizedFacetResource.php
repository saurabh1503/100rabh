<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Resources\Facets\Dropdown;

use Magento\Framework\DB\Select;
use Manadev\ProductCollection\Contracts\Facet;
use Manadev\ProductCollection\Contracts\FacetResource;
use Manadev\ProductCollection\Facets\Dropdown\OptimizedFacet;
use Zend_Db_Expr;

class OptimizedFacetResource extends FacetResource
{

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
        /* @var $facet OptimizedFacet */
        $this->prepareSelect($select, $facet);
        $result = $this->getConnection()->fetchAll($select);
        $minimumOptionCount = $facet->getHideWithSingleVisibleItem() ? 2 : 1;
        return count($result) >= $minimumOptionCount ? $result : false;
    }

    public function prepareSelect(Select $select, OptimizedFacet $facet) {
        $this->helperResource->clearFacetSelect($select);

        $db = $this->getConnection();

        $selectedOptionIds = $facet->getSelectedOptionIds();
        $isSelectedExpr = $selectedOptionIds !== false
            ? "`eav`.`value` IN (" . implode(',', $selectedOptionIds). ")"
            : "1 <> 1";

        $fields = array(
            'sort_order' => new Zend_Db_Expr("`o`.`sort_order`"),
            'value' => new Zend_Db_Expr("`eav`.`value`"),
            'label' => new Zend_Db_Expr("COALESCE(`vs`.`value`, `vg`.`value`)"),
            'is_selected' => new Zend_Db_Expr($isSelectedExpr),
        );
        $select
            ->joinInner(array('eav' => $this->getTable('catalog_product_index_eav')),
                "`eav`.`entity_id` = `e`.`entity_id` AND
                {$db->quoteInto("`eav`.`attribute_id` = ?", $facet->getAttributeId())} AND
                {$db->quoteInto("`eav`.`store_id` = ?", $this->getStoreId())}",
                array('count' => "COUNT(DISTINCT `eav`.`entity_id`)")
            )
            ->joinInner(array('o' => $this->getTable('eav_attribute_option')),
                "`o`.`option_id` = `eav`.`value`", null)
            ->joinInner(array('vg' => $this->getTable('eav_attribute_option_value')),
                $db->quoteInto("`vg`.`option_id` = `eav`.`value` AND `vg`.`store_id` = ?", 0), null)
            ->joinLeft(array('vs' => $this->getTable('eav_attribute_option_value')),
                $db->quoteInto("`vs`.`option_id` = `eav`.`value` AND `vs`.`store_id` = ?", $this->getStoreId()), null)
            ->columns($fields)
            ->group($fields);

        return $select;
    }

    public function getFilterCallback(Facet $facet) {
        return $this->helperResource->dontApplyFilterNamed($facet->getName());
    }
}