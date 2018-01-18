<?php
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$state->setAreaCode('frontend');

$date = date('Y-m-d H:i:s');
$fromDate = date('Y-m-d H:i:s', strtotime($date .' -1 day'));


$ordercollection = $objectManager->get('\Magento\Sales\Model\ResourceModel\Order\CollectionFactory')->create();
$collectionday = 	$ordercollection->addAttributeToSelect('*')
								->addFieldToFilter('created_at', array('from'=>$fromDate, 'to'=>$date))
								->addFieldToFilter('status', array('nin' => array('cancelled')));
								
											

$total = 0;
$sample =0;	
$allqty=0;
$sampleqty=0;	
				
 foreach($collectionday as $order){
	 $totalqty = $order->getData('total_qty_ordered');
	 $grandtotal = $order->getData('grand_total');
	 $subtotal = $order->getData('subtotal');


	 
	 if ($subtotal == 0.99){
		 $samplesum = $order->getData('grand_total');
		 $totalqty = $order->getData('total_qty_ordered');
		 $sample += $samplesum;
		 $sampleqty += $totalqty;
		 
	 }
	 else{
		 	 $total += $grandtotal;
			$allqty += $totalqty;
	 }

 }
 
$totalqtysales = $sampleqty+$allqty;
$totalsales = $total+$sample;
$strtmonth =date('Y-m-01 H:i:s',strtotime('this month'));
$ordercollection1 = $objectManager->get('\Magento\Sales\Model\ResourceModel\Order\CollectionFactory')->create();
$collectionmonth= 	$ordercollection1->addAttributeToSelect('*')
								->addFieldToFilter('created_at', array('from'=>$strtmonth, 'to'=>$date))
								->addFieldToFilter('status', array('nin' => array('cancelled')));




								
$totalmonth = 0;
$samplemonth =0;	
$allqtymonth=0;
$sampleqtymonth=0;	
foreach($collectionmonth as $ordermonth){
	 $totalqtymonth = $ordermonth->getData('total_qty_ordered');
	 $grandtotalmonth = $ordermonth->getData('grand_total');
	 $subtotalmonth = $ordermonth->getData('subtotal');


	 
	 if ($subtotalmonth == 0.99){
		 $samplesummonth = $ordermonth->getData('grand_total');
		 $totalqtymonth = $ordermonth->getData('total_qty_ordered');
		 $samplemonth += $samplesummonth;
		 $sampleqtymonth += $totalqtymonth;
		 
	 }
	 else{
		 	 $totalmonth += $grandtotalmonth;
			$allqtymonth += $totalqtymonth;
	 }

 }
 
$totalqtysalesmonth = $sampleqtymonth+$allqtymonth;
$totalsalesmonth = $totalmonth+$samplemonth;

$report = [
                'day_date' => date("F j, Y"),
                'day_total_material_sales_qty' => $allqty,
                'day_total_material_sales' => $total,
                'day_total_material_sales_sample_qty' => $sampleqty,
                'day_total_material_sales_sample_sale' => $sample,
                'day_total_sale_qty' => $totalqtysales,
                'day_total_sale' => $totalsales,
                'month_date' => date("F, Y"),
                'month_total_material_sales_qty' => $allqtymonth,
                'month_total_material_sales' => $totalmonth,
                'month_total_material_sales_sample_qty' => $sampleqtymonth,
                'month_total_material_sales_sample_sale' => $samplemonth,
                'month_total_sale_qty' => $totalqtysalesmonth,
                'month_total_sale' => $totalsalesmonth,
            ];
//echo "<pre>"; print_r($report);die;
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($report);

            $transportBuilder = $objectManager->create('\Magento\Framework\Mail\Template\TransportBuilder');
				$transport = $transportBuilder
                ->setTemplateIdentifier(25)
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => $postObject])
                ->setFrom(['name' => 'Info','email' => 'info@efloors.com'])
                ->addTo(['bgeskey@efloors.com','malbert@fcainc.com','saurabhd@chetu.com'])
                ->getTransport();
            $transport->sendMessage();
	//echo "sent";							
?>




