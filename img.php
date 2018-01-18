<?php
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$state->setAreaCode('frontend');
	
	$objectManager = Magento\Framework\App\ObjectManager::getInstance();
	$productId = $objectManager->get('Magento\Catalog\Model\Product')->getIdBySku('arm');
    $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
        
    $images = $product->getMediaGalleryImages();
    foreach($images as $child){ 
        echo $child->getUrl();
} 

?>