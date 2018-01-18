<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core\PageTypes;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Manadev\Core\Contracts\PageType;
use Manadev\LayeredNavigation\Resources\Collections\FilterCollection;
use Magento\Sitemap\Model\ResourceModel\Catalog\CategoryFactory as SitemapCategoryFactory;

class CategoryPage extends PageType
{
    /**
     * @var SitemapCategoryFactory
     */
    protected $sitemapCategoryFactory;

    public function __construct(SitemapCategoryFactory $sitemapCategoryFactory) {
        $this->sitemapCategoryFactory = $sitemapCategoryFactory;
    }

    /**
     * @param FilterCollection $filters
     */
    public function limitFilterCollection($filters) {
        $filters->addFieldToFilter('is_enabled_in_categories', 1);
    }

    /**
     * @param Store $store
     * @return array
     */
    public function getSitemapItems($store) {
        return $this->sitemapCategoryFactory->create()->getCollection($store->getId());
    }
}