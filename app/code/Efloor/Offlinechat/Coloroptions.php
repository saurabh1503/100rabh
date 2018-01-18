<?php

/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Efloor\Common\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;

class Coloroptions extends \Magento\Framework\View\Element\Template {
    
     protected $_registry;
	 protected $productRepository; 
	 protected $productCollectionFactory; 
    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
            \Magento\Catalog\Block\Product\Context $context, 
            \Magento\Framework\Registry $registry,
			ProductRepositoryInterface $productRepository,
			\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, 
            array $data = []
    ) {
        $this->registry = $registry;
		$this->productRepository = $productRepository;
		$this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context,$data);
    }

 
	
	public function getCurrentProduct()
    {      
		$currentProduct = $this->registry->registry('current_product');
        $productID= $currentProduct->getId();
		$product = $this->productRepository->getById($productID);
		return $product;
    }

   
 public function getTermModeling() {

        return $this->getCurrentProduct()->getTrimsMoldings();
    }

    public function getManufacturer() {

        return $this->getCurrentProduct()->getManufacturer();
    }

    public function getProductTypeID() {

        return $this->getCurrentProduct()->getPproducttypeid();
    }

    public function getProductCollection() {

        return $this->getCurrentProduct()->getCollection_name();
    }

    public function getStyleName() {

        return $this->getCurrentProduct()->getStyle_name();
    }

    public function getProductDesigner() {

        return $this->getCurrentProduct()->getDesigner();
    }

    public function getProductStyle() {

        return $this->getCurrentProduct()->getStyle();
    }

    public function getProductLocking() {

        return $this->getCurrentProduct()->getLocking();
    }

    public function getProductVaccumType() {

        return $this->getCurrentProduct()->getVacuum_type();
    }

    public function getProductSku() {

        return $this->getCurrentProduct()->getSku();
    }
    
      public function getProductEcolor() {

        return $this->getCurrentProduct()->getEcolor();
    }

	public function getOtherColorOption() {
	    $manufacturer = '';
		$productTypeId = $this->getCurrentProduct()->getPproducttypeid();
		if($productTypeId == 8 || $productTypeId == 10 || $productTypeId == 0 || $productTypeId > 10) {
			return false; 
		}
		$style_name = $this->getCurrentProduct()->getStyleName();
		$style = $this->getCurrentProduct()->getStyle();
		$collection_name = $this->getCurrentProduct()->getCollectionName();
		$collection = $this->productCollectionFactory->create();
		$color = $this->getProductEcolor();
        $designer = $this->getProductDesigner();
        $collection->addAttributeToSelect(['entity_id','image']);
		
		/**** Get Attribute set name ****/
		$manufacturerId = '';
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$attributeSet = $objectManager->create('Magento\Eav\Api\AttributeSetRepositoryInterface');
		if(!empty($attributeSet)){  ;
			$attributeSetRepository = $attributeSet->get($this->getCurrentProduct()->getAttributeSetId());
		    $attribute_set_name = $attributeSetRepository->getAttributeSetName(); 
			$attribute_set_name_main = substr($attribute_set_name, strpos($attribute_set_name, "-") + 1);	
			if($attribute_set_name_main=='area-rug') {
					$attribute_set_name_main = 'arearugs';
			}
			if($attribute_set_name_main=='tile') {
					$attribute_set_name_main = 'luxuryvinyl';
			}
			$manufacturer = $attribute_set_name_main.'_manufacturer';
			$manufacturer = str_replace('-', '', $manufacturer);
			if(!empty($manufacturer)){
				
				$manufacturerId = $this->getCurrentProduct()->getData($manufacturer);
		
			}
			
		}
		/**** End Attribute set name ****/
	    $productTypeId = $this->getCurrentProduct()->getPproducttypeid();
		$construction = $this->getCurrentProduct()->getConstruction();
		switch ($productTypeId) {
        case 1:
		
		$width = $this->getCurrentProduct()->getWidth();
		$length = $this->getCurrentProduct()->getLength();
		$origin = $this->getCurrentProduct()->getOrigin();
		$material = $this->getCurrentProduct()->getMaterial();
		$shape = $this->getCurrentProduct()->getArearugsShape();
		$theme = $this->getCurrentProduct()->getArearugsTheme();
		$colorgroup = $this->getCurrentProduct()->getColorGroup();
		
		
         if (!empty($collection_name)) {
                    $collection->addAttributeToFilter('collection_name', ['eq' => "$collection_name"]);
         }       
		
		if (!empty($manufacturerId)) {
           $collection->addAttributeToFilter($manufacturer, ['eq' => "$manufacturerId"]);
        }
		
		if (!empty($width)) {
			$collection->addAttributeToFilter('width', ['eq' => "$width"]);
		}
		
		if (!empty($length)) {
			$collection->addAttributeToFilter('length', ['eq' => "$length"]);
		}
		if (!empty($construction)) {
			$collection->addAttributeToFilter('construction', ['eq' => "$construction"]);
		}
		if (!empty($origin)) {
			$collection->addAttributeToFilter('origin', ['eq' => "$origin"]);
		}
		if (!empty($material)) {
			$collection->addAttributeToFilter('material', ['eq' => "$material"]);
		}
		if (!empty($shape)) {
			$collection->addAttributeToFilter('arearugs_shape', ['eq' => "$shape"]);
		}	
		if (!empty($style)) {
			$collection->addAttributeToFilter('style', ['eq' => "$style"]);
		}
		$collection->addAttributeToFilter('pproducttypeid', ['eq' => 1]);		
		break;
		
		case 3:   

		if (!empty($manufacturerId)) {
           $collection->addAttributeToFilter($manufacturer, ['eq' => "$manufacturerId"]);
        }
		
	  
		if (!empty($style_name)) {
			$collection->addAttributeToFilter('style_name', ['eq' => "$style_name"]);
		} 
		break;
		
		case 6:
		if (!empty($style_name)) {
			$collection->addAttributeToFilter('style_name', ['eq' => "$style_name"]);
			$collection->addAttributeToFilter('pproducttypeid', ['neq' => 8]);
		} 
		
		break;
		
		
		case 7:   
		if (!empty($manufacturerId)) {
           $collection->addAttributeToFilter($manufacturer, ['eq' => "$manufacturerId"]);
        }
				
		if (!empty($style_name)) {
			$collection->addAttributeToFilter('style_name', ['eq' => "$style_name"]);
		} 
		break;
		
		
		default: 
		  if (!empty($collection_name)) {
                    $collection->addAttributeToFilter('collection_name', ['eq' => "$collection_name"]);
         }       
		
		if (!empty($manufacturerId)) {
           $collection->addAttributeToFilter($manufacturer, ['eq' => "$manufacturerId"]);
        }
		
		
		if (!empty($construction)) {
			$collection->addAttributeToFilter('construction', ['eq' => "$construction"]);
		}
		
		if (!empty($style_name)) {
			$collection->addAttributeToFilter('style_name', ['eq' => "$style_name"]);
		} 
		
		
		
	}

		$sku = $this->getCurrentProduct()->getSku();
		$collection->addAttributeToFilter('sku', array('neq' => "$sku"));
		$collection->addAttributeToFilter('status', array('eq' => 1));
		
		return $collection; 
	
	
	}
	
	
	public function hasColorOptions(){
		$productTypeId = $this->getCurrentProduct()->getPproducttypeid();
		if($productTypeId == 8 || $productTypeId == 10 || $productTypeId == 0 || $productTypeId > 10) {
			return false; 
		}
		return true;
	}
	


}

