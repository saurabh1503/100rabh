<?php
namespace Fcamodule\Productimport\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderPlaceAfter implements ObserverInterface
{
    
    protected $order;
	protected $quoteFactory;
	protected $cart;
	protected $objectmanager;
     public function __construct(
	 \Magento\Sales\Model\Order $order,
	 \Magento\Framework\ObjectManagerInterface $objectmanager,
	 \Magento\Quote\Model\QuoteFactory $quoteFactory,
	 \Magento\Catalog\Model\ProductRepository $productRepository,
	 \Magento\Checkout\Model\Cart $cart
	 )
		{
			
        $this->order = $order;
        $this->objectmanager = $objectmanager;
		$this ->quoteFactory = $quoteFactory;
		$this->_productRepository = $productRepository;
		$this->_cart = $cart;
		
		}
	

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		// $server = '12.207.213.14';
			// $myDB = "efloors_backend_test"; 
			// $conn = mssql_connect($server, 'chetu', 'ch3tuf0ry0u');
			// $selected = mssql_select_db($myDB, $conn)
			  // or die("Couldn't open database $myDB");
			  
       $orderId = $observer->getEvent()->getOrderIds();
        $order = $this->order->load($orderId);
		$orderrId = $order->getId();
        $itemCollection = $order->getItemsCollection();
        $customerId = $order->getCustomerId();
		$customerName = $order->getCustomerName();
		$email=$order->getCustomerEmail();
		$totall = $order->getGrandTotal();
		$shippingAddress = $order->getShippingAddress();
		$address=$shippingAddress->getData('street');
		$region=$shippingAddress->getData('region');
		$country=$shippingAddress->getData('country_id');
		$telephone = $shippingAddress->getTelephone();
		$postcode = $shippingAddress->getPostcode();
		$city = $shippingAddress->getCity();
		
		$ordShipType = $order->getShippingDescription(); //ordShipType
		$ordShipping=$order->getShippingAmount(); //ordShipping
		$ordStateTax=$order->getTaxAmount(); //ordStateTax
		$ordDiscountText=$order->getDiscountDescription(); //ordDiscountText
		$ordIP=$order->getRemoteIp(); //$ordIP
		$payment = $order->getPayment();
		$method = $payment->getMethodInstance();
		$ordPaymentType = $method->getTitle();
		$ordPayProvider= $payment->getCcType();
		
		$quotess=$order->getQuoteId();
		
		
			
		$order12 = $this->objectmanager->create('Magento\Sales\Model\Order')->load($orderId);
		$orderItems = $order12->getAllItems();	
	
		foreach($orderItems as $item)
		{	
			
			$cartID=$item->getItemId();
			$cartProdId=$item->getProductId();
			$cartProdName=$item->getName();
			$cartProdPrice=$item->getPrice();
			$cartQuantity=$item->getItemsCount();
			$cartOrderID=$item->getOrderId();
			
			
			
			  
			// $sql2 = "SET IDENTITY_INSERT CART ON;
			// INSERT INTO CART (cartID,cartProdId,cartProdName,cartProdPrice,cartQuantity,cartOrderID) 
			// VALUES ('$cartID','$cartProdId','$cartProdName','$cartProdPrice','$cartQuantity','$cartOrderID')
			// SET IDENTITY_INSERT CART OFF;";
			// $stmt2 = mssql_query($sql2,$conn);
			// if ($stmt2 === false) {
				// die('MSSQL error: ' . mssql_get_last_message());
			// }
		}		
		
			// $sql1 = "SET IDENTITY_INSERT ORDERS ON;
			// INSERT INTO ORDERS (ordPayProvider,ordPaymentType,ordShipState,ordCountry,ordIP,ordDiscountText,ordStateTax,ordShipping,ordShipType,ordID,ordName,ordAddress,ordCity,ordZip,ordEmail,ordPhone,ordShipName,ordShipAddress,ordShipZip,ordShipCountry,ordTotal,ordShipcity,ordAddress2,ordState,ordShipAddress2) 
			// VALUES ('$ordPayProvider','$ordPaymentType','$region','$country','$ordIP','$ordDiscountText','$ordStateTax','$ordShipping','$ordShipType','$orderrId','$customerName','$country','$city','$postcode','$email','$telephone','$customerName','$country','$postcode','$country','$totall','$city','$address','$region','$address')
			// SET IDENTITY_INSERT ORDERS OFF;";
			// $stmt1 = mssql_query($sql1,$conn);
			// if ($stmt1 === false) {
				// die('MSSQL error: ' . mssql_get_last_message());
			// }

			//
			$product   = $this->_productRepository->getById($cartProdId);
			$pindex    = $product->getArea();
			$product->setArea($pindex+10);
            $product->save();
			
			echo $product->getArea(); die;
			
			
			//
			
    }
} 


