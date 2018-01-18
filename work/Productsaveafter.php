<?php

namespace Efloors\Producturl\Observer;

use Magento\Framework\Event\ObserverInterface;

class Productsaveafter implements ObserverInterface
{    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {	
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_product = $observer->getProduct(); 
		$changeprice = $this->changePrice($_product);
		//$updateUrl = $this->updateUrl($_product);
		
		
    }

	
	
	
	public function changePrice($_product){
		
		 
		
		$cartonprice = $_product->getData('carton_price');
		$soldby = $_product->getData('sold_by');
		if(!empty($cartonprice) && !empty($soldby))  { 
		$calculatedprice = $cartonprice/$soldby;
		$_product->setSpecialPrice($calculatedprice); //1.79
		$_product->getResource()->saveAttribute($_product, 'special_price');
		}
	}
		
    public function updateUrl($_product){
			
			$product_id = $_product->getId();
		$product_created = $_product->getData('created_at');
		$product_updated = $_product->getData('updated_at');
		if($product_created == $product_updated){
		$custom_url = '';
		$custom_url = $this->createCustomUrl($_product,$objectManager);
		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();
		$eavtableName = $resource->getTableName('catalog_product_entity_varchar');
		$urltableName = $resource->getTableName('url_rewrite');
	    $eav_sql = "Update " . $eavtableName . " Set value = '/".$custom_url."' where attribute_id = '126' AND entity_id = ".$product_id;
		
		$url_sql = "Update " . $urltableName . " SET url_rewrite.request_path = IF(url_rewrite.target_path LIKE '%category%', concat('/','','".$custom_url.".htm'), '".$custom_url.".htm') where entity_id = '".$product_id."' and entity_type = 'product'";
		$connection->query('SET FOREIGN_KEY_CHECKS=0');
		$connection->query($eav_sql);
		$connection->query($url_sql);
		$connection->query('SET FOREIGN_KEY_CHECKS=1');
			
		}
			
	}
	
	public function createCustomUrl($_product,$obj) {
		
		$url = '';
		$url .= $_product->getName();
		$url = preg_replace('#[^0-9a-z]+#i', '-', $url);
		
		$product_type_id = $_product->getData('pproducttypeid');
		// if($product_type_id != 1){
			// if(empty($section_id)){
				// $url .= '/all-products/';
			// } else if($section_name == 'brand'){
				// $url .= '/';
			// } else {
				// $url .= '/section-name/';
			// }
			
		// }
		$catagory_name = '';
		if($product_type_id != 1){
        $cats = $_product->getCategoryIds();
        if(count($cats) ){
			$_categoryFactory = $obj->get('Magento\Catalog\Model\CategoryFactory');
            $firstCategoryId = $cats[0];
            $_category = $_categoryFactory->create()->load($firstCategoryId);
            $catagory_name =  $_category->getName();
			$catagory_name = $_category->getName();
			if(!empty(strpos($catagory_name,'brand'))){
				$catagory_name = '';
			 }
			} else {
				$catagory_name = 'all-products';
			}
		}
		if(!empty($catagory_name)) {
			$catagory_name = '/'.$catagory_name.'/';
		}
		$url .= $catagory_name.'catalog_'.$_product->getSku();
		$url = strtolower($url);
		
		return $url;
		
	}
	
	
}
