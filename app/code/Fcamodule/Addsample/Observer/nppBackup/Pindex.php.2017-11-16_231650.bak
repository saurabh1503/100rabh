<?php
    /**
     * DocResearch Customer CustomPrice Observer
     *
     * @category    DocResearch
     * @package     DocResearch_Customer
     *
     */
    namespace Fcamodule\Addsample\Observer;
 
    use Magento\Framework\Event\ObserverInterface;
    use Magento\Framework\App\RequestInterface;
	
	
    class CustomPrice implements ObserverInterface
    {
	
	
        public function execute(\Magento\Framework\Event\Observer $observer) {
			
			 $product_id = $observer->getProduct()->getId();
			 $product_price = $this->getPriceByCatron($product_id);
             $item = $observer->getEvent()->getData('quote_item');         
             $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
             //$price = 100; //set your price here
            $item->setCustomPrice($product_price);
            $item->setOriginalCustomPrice($product_price);
            $item->getProduct()->setIsSuperMode(true);
        }
		
		/**
		* Check customer and get customer store 
		* @var $product_id (int)
		* @return array.
		*/
		 public function getPriceByCatron($product_id) {
		 
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);
			$carton_price = $product->getCartonPrice();
			$mystring = $product->getSku();	
			
			$psfpercarton = $product->getData('sold_by');
			$pshowprice = $product->getData('pshowprice');
			$calculator = false;
			if(($pshowprice == 0 || $pshowprice == 1) && !empty($psfpercarton)) {		
				$calculator = true;		
			}

			$pos = strpos($mystring, 'sam');
			if ($pos === false && $calculator == true) {
			if(!empty($carton_price)) {
				
				return $carton_price;
			}
			
			return false;
			}
		 }
		

		
		
    }