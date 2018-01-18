<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Resources\Collections;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class FilterCollection extends AbstractCollection {
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Manadev\LayeredNavigation\Models\Filter', 'Manadev\LayeredNavigation\Resources\FilterResource');
    }

    public function systemWide() {
        $this->getSelect()->where("`main_table`.`store_id` = ?", 0);

        return $this;
    }

    public function storeSpecific($storeId) {
        $this->getSelect()->where("`main_table`.`store_id` = ?", $storeId);

        return $this;
    }

    public function paramName($paramName) {
        $this->getSelect()->where("`main_table`.`param_name` = ?", $paramName);

        return $this;
    }

    public function orderByPosition() {
        $this->getSelect()->order('position ASC');

        return $this;
    }
}