<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core\PageTypes;

use Manadev\Core\Contracts\PageType;

class QuickSearchPage extends PageType
{
    /**
     * @param \Manadev\LayeredNavigation\Resources\Collections\FilterCollection $filters
     */
    public function limitFilterCollection($filters) {
        $filters->addFieldToFilter('is_enabled_in_search', 1);
    }
}