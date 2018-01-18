<?php

namespace Fcamodule\Productimport\Observer;

use Magento\Framework\Event\ObserverInterface;

class Productsaveafter implements ObserverInterface
{    
	public function __construct(
		\Magento\CatalogInventory\Api\StockStateInterface $stockItem
	   )
	  {
		$this->stockItem = $stockItem;
	  }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
        $_product = $observer->getProduct(); 
        $id=$_product->getSku();
        $name=$_product->getName(); 
        $price=$_product->getPrice(); 
        $qty= $this->stockItem->getStockQty($_product->getId(), $_product->getStore()->getWebsiteId());
        $_sku=$_product->getSku(); 
        $attr=$_product->getAttributeSetId();
		$ids=$_product->getId();
			
			// $server = '12.207.213.14';
			// $myDB = "efloors_backend_test"; 
			// $conn = mssql_connect($server, 'chetu', 'ch3tuf0ry0u');
			// $selected = mssql_select_db($myDB, $conn)
			  // or die("Couldn't open database $myDB");
			
			// $query = mssql_query("SELECT pID FROM products where pID ='$id'");
			// $count = mssql_num_rows($query);
			// if ($count < 1){
			// $sql = "INSERT INTO products (pID,pName,pPrice,pSfPerQty,pMPSKU,pProductTypeID,productID) VALUES ('$id','$name','$price','$qty','$_sku','$attr','$ids')";
			// $stmt = mssql_query($sql,$conn);
			// if ($stmt === false) {
				// $writer1 = new \Zend\Log\Writer\Stream(BP . '/var/log/product_save.log');
				// $logger1 = new \Zend\Log\Logger();
				// $logger1->addWriter($writer1);
				// $logger1->info('MSSQL PRODUCTS ENTRY FAILED');	
			// }
			// }

			 }
     catch (\Exception $e) {
        $this->logger->critical($e->getMessage());
    }
			
			}

    }   
