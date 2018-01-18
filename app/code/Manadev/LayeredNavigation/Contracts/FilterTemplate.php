<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */


namespace Manadev\LayeredNavigation\Contracts;

use Magento\Catalog\Model\Layer;
use Manadev\LayeredNavigation\Models\Filter;
use Manadev\ProductCollection\Contracts\ProductCollection;

interface FilterTemplate {
    /**
     * @param Filter $filter
     * @return string
     */
    public function getFilename(Filter $filter);

    /**
     * @return string
     */
    public function getAppliedItemFilename();

    /**
     * Registers filtering and counting logic with product collection
     *
     * @param ProductCollection $productCollection
     * @param Filter $filter
     */
    public function prepare(ProductCollection $productCollection, Filter $filter);

    /**
     * @param Filter $filter
     * @return mixed|bool
     */
    public function getAppliedOptions(Filter $filter);

    /**
     * @param ProductCollection $productCollection
     * @param Filter $filter
     * @return array
     */
    public function getAppliedItems(ProductCollection $productCollection, Filter $filter);

    public function isLabelHtmlEscaped();

    public function getTitle();
}