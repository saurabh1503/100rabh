<?php
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$file = fopen("products.csv","r");
$all_rows = array();
$header = null;
while ($row = fgetcsv($file)) {
     if ($header === null) {
        $header = $row;
         continue;
     }
     $all_rows[] = array_combine($header, $row);
 }
 try{
foreach($all_rows as $data){
echo $data['sku'].'<br/>';
$objectManager1 = \Magento\Framework\App\ObjectManager::getInstance();
$product = $objectManager1->get('Magento\Catalog\Model\Product');
$product->load($product->getIdBySku($data['sku']));
if($product->getId()){	
	$product->setMetaTitle($data['meta_title']);
	$product->setMetaDescriptionWarranty($data['meta_title']);
	$product->setWarranty($data['warranty']);
	$product->setInstallation($data['installation']);
    $product->save();	
}
}
 }catch(Exception $e){
	 echo $e->getMessage.'<br/>';
 }

///////////////////////////////////
//$_product = $objectManager->create('Magento\Catalog\Model\Product')->load();
?>