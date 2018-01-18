<?php

namespace Fcamodule\Addsample\Plugin;


use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item;

class DefaultItem
{

    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    public function aroundGetItemData($subject, \Closure $proceed, Item $item)
    {
        $data = $proceed($item);

        /** @var Product $product */
        $product = $this->productRepo->getById($item->getProduct()->getId());
        $attributes = $product->getAttributes();

        $atts = [
            "product_name" => strip_tags($attributes['name']->getFrontend()->getValue($product)),
            "product_sku" => $attributes['sku']->getFrontend()->getValue($product)
        ];

        return array_merge($data, $atts);
    }
}