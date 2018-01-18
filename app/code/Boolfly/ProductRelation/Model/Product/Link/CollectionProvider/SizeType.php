<?php

namespace Boolfly\ProductRelation\Model\Product\Link\CollectionProvider;

class SizeType implements \Magento\Catalog\Model\ProductLink\CollectionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts(\Magento\Catalog\Model\Product $product)
    {
        $products = $product->getSizetypeProducts();

        if (!isset($products)) {
            return [];
        }

        return $products;
    }
}