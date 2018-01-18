<?php

namespace Boolfly\ProductRelation\Model;

class Product extends \Magento\Catalog\Model\Product
{
    /**
     * Retrieve array of custom type products
     *
     * @return array
     */
    public function getCustomtypeProducts() 
    {
        if (!$this->hasCustomTypeProducts()) {
            $products = [];
            foreach ($this->getCustomTypeProductCollection() as $product) {
                $products[] = $product;
            }
            $this->setCustomtypeProducts($products);
        }
        return $this->getData('customtype_products');
    }
	
	
	 /**
     * Retrieve array of size type products
     *
     * @return array
     */
    public function getSizeypeProducts() 
    {
        if (!$this->hasSizeTypeProducts()) {
            $products = [];
            foreach ($this->getSizeTypeProductCollection() as $product) {
                $products[] = $product;
            }
            $this->setSizetypeProducts($products);
        }
        return $this->getData('sizetype_products');
    }
	
    /**
     * Retrieve custom type products identifiers
     *
     * @return array
     */
    public function getCustomtypeIds() 
    {
        if (!$this->hasCustomtypeProductIds()) {
            $ids = [];
            foreach ($this->getCustomtypeProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setCustomtypeProductIds($ids);
        }
        return $this->getData('customtype_product_ids');
    }
	
	 /**
     * Retrieve size type products identifiers
     *
     * @return array
     */
    public function getSizetypeIds() 
    {
        if (!$this->hasSizetypeProductIds()) {
            $ids = [];
            foreach ($this->getSizetypeProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setSizetypeProductIds($ids);
        }
        return $this->getData('sizetype_product_ids');
    }
    /**
     * Retrieve collection custom type product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getCustomTypeProductCollection() 
    {
        $collection = $this->getLinkInstance()->useCustomtypeLinks()->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }
	
	 /**
     * Retrieve collection size type product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getSizeTypeProductCollection() 
    {
        $collection = $this->getLinkInstance()->useSizetypeLinks()->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }
	
	
    /**
     * Retrieve collection custom type link
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Collection
     */
    public function getCustomTypeLinkCollection() 
    {
        $collection = $this->getLinkInstance()->useCustomtypeLinks()->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }
    
	
	 /**
     * Retrieve collection size type link
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Collection
     */
    public function getSizeTypeLinkCollection() 
    {
        $collection = $this->getLinkInstance()->useSizetypeLinks()->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }
}