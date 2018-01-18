<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation;

use Magento\Store\Model\StoreManagerInterface;
use Manadev\LayeredNavigation\Models\Filter;
use Manadev\Core\Helper as CoreHelper;

class Helper {
    /**
     * @var Filter[]
     */
    protected $filters;
    /**
     * @var Factory
     */
    protected $factory;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var CoreHelper
     */
    protected $coreHelper;

    public function __construct(Factory $factory, StoreManagerInterface $storeManager, CoreHelper $coreHelper) {
        $this->factory = $factory;
        $this->storeManager = $storeManager;
        $this->coreHelper = $coreHelper;
    }

    /**
     * @return \Manadev\LayeredNavigation\Resources\Collections\FilterCollection
     */
    public function getAllFiltersForCurrentStore() {
        if (!$this->filters) {
            $filters = $this->factory->createFilterCollection()
                ->storeSpecific($this->storeManager->getStore()->getId());

            if ($pageType = $this->coreHelper->getPageType()) {
                $pageType->limitFilterCollection($filters);
            }


            $filters->orderByPosition();
            $this->filters = $filters;
        }

        return $this->filters;
    }
}