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
            
			if($_SERVER['REMOTE_ADDR'] == '50.192.171.26') {
			$item->setCustomPrice(0.01);
			$item->setOriginalCustomPrice(0.01);			
			}else{
			$item->setCustomPrice($product_price);
			$item->setOriginalCustomPrice($product_price);
            }
			//$item->setOriginalCustomPrice($product_price);
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
			$customerSession = $objectManager->create('Magento\Customer\Model\Session');
			$customer = '';
			if($customerSession->isLoggedIn()) {
			$customer = $customerSession->getCustomer()->getGroupId();
			}
			$carton_price = $product->getCartonPrice();
			$mystring = $product->getSku();	
			
			$psfpercarton = $product->getData('sold_by');
			$pshowprice = $product->getData('pshowprice');
			$calculator = false;
			if(($pshowprice == 0 || $pshowprice == 1) && !empty($psfpercarton)) {		
				$calculator = true;		
			}
			$sampleprice = $product->getPriceInfo()->getPrice('final_price')->getValue();
			$pos = strpos($mystring, 'sam');
			if (($pos === false && $calculator == true) || $customer == 4) {
			if(!empty($carton_price) ) {
				
				return $carton_price;
			}
			
			// if(!empty($carton_price) && !empty($psfpercarton)) {
			    // $calculatedprice = $carton_price/$soldby;
				// $calculatedprice = round($calculatedprice,2);
				// return $calculatedprice;
			// }
			
			
			else{
				if($_SERVER['REMOTE_ADDR'] == '50.192.171.26') {
					return 0.01;
				}else{
				return $sampleprice;
				}
			}
			
			return false;
			}
		 }
		

		
		
    }