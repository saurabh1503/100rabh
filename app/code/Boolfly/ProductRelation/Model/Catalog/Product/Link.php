<?php

namespace Boolfly\ProductRelation\Model\Catalog\Product;

class Link extends \Magento\Catalog\Model\Product\Link
{
    const LINK_TYPE_CUSTOMTYPE = 11;
    const LINK_TYPE_SIZETYPE = 12;

    /**
     * @return \Magento\Catalog\Model\Product\Link $this
     */
    public function useCustomtypeLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_CUSTOMTYPE);
        return $this;
    }

	  /**
     * @return \Magento\Catalog\Model\Product\Link $this
     */
    public function useSizetypeLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_SIZETYPE);
        return $this;
    }

	
    /**
     * Save data for product relations
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return \Magento\Catalog\Model\Product\Link
     */
    public function saveProductRelations($product)
    {
        parent::saveProductRelations($product);

        $data = $product->getCustomtypeData();
		$data_size = $product->getSizetypeData();
        if (!is_null($data)) {
			//echo "<pre>"; print_r($data); die();
            $this->_getResource()->saveProductLinks($product->getId(), $data, self::LINK_TYPE_CUSTOMTYPE);
        }
		if (!is_null($data_size)) {
            $this->_getResource()->saveProductLinks($product->getId(), $data, self::LINK_TYPE_SIZETYPE);
        }
    }
}
