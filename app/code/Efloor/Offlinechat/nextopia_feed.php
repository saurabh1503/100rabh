<?php
use Magento\Framework\App\Bootstrap;
include('../app/bootstrap.php');
ini_set('max_execution_time', -1);
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$state->setAreaCode('frontend');


$imgurl= $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product';
$products = $objectManager->get('Magento\Catalog\Model\ProductFactory');
$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
$productCollection->addAttributeToSelect('name')
			->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
			->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
			//->addAttributeToFilter('entity_id', array('gt' => 120144))
			//->setPageSize(50000)
			->load();

$filePath = 'nextopia-feed.csv';
if (file_exists($filePath)) {
    $fh = fopen($filePath, 'a');     
} else {
    $fh = fopen($filePath, 'w');
}  

$header1 = array('id','sku','title','link','image_link','price','description','accessories_category','accessories_manufacturer','arearugs_colors','arearugs_manufacturer','arearugs_shape','arearugs_theme','bamboo_colors','bamboo_construction','bamboo_installation','bamboo_manufacturer','bamboo_thickness','bamboo_width','carpettile_manufacturer','category_gear','climate','clocks_finish','clocks_material','clocks_style','clocks_type','collar','cork_colors','cork_installation','cork_manufacturer','cork_thickness','cork_width','eco_collection','erin_recommends','format','glasstile_colors','glasstile_composition','glasstile_manufacturer','glasstile_tilesize','glasstile_visual','hardwood_colors','hardwood_construction','hardwood_finish','hardwood_manufacturer','hardwood_species','hardwood_thickness','hardwood_width','laminate_colors','laminate_manufacturer','laminate_thickness','laminate_visual','luxuryvinyl_colors','luxuryvinyl_installation','luxuryvinyl_manufacturer','luxuryvinyl_tilesize','luxuryvinyl_visual','new','pattern','pcategory','performance_fabric','product_type1','sale','sleeve','styles_name','vacuum_manufacture','vacuum_type','visual','pshowprice','pPopularityIndex','psell','samplesku');
 
fputcsv($fh, $header1); 

foreach($productCollection as $_product){
	
$product = $products->create();
	$product->load($_product->getID());	
	
$specialPrice = $product->getSpecialPrice();
if($specialPrice){
$price = $specialPrice;
} 
else{
	$price = $product->getPrice();
}
// $images= $imgurl.$product->getImage();

// $ch = curl_init($images);
// curl_setopt($ch, CURLOPT_NOBODY, true);
// curl_exec($ch);
// $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// curl_close($ch);
// if($retcode==200) {
    // $product_images = $images;
// }
// else
// {
    // $product_images ="https://qa.efloors.com/pub/static/frontend/TemplateMonster/Spiceli/en_US/Magento_Catalog/images/product/placeholder/image.jpg";
// }
$optionText2= $product->getResource()->getAttribute('arearugs_theme')->getFrontend()->getValue($product);
$optionText = $product->getResource()->getAttribute('arearugs_colors')->getFrontend()->getValue($product);
$rowNew = array();
$rowNew['id'] = $product->getId();
$rowNew['sku'] = $product->getSku();
$rowNew['title'] = $product->getName();
$rowNew['link'] = $product->getProductUrl();
$rowNew['image_link'] = 'https://www.gowebpix.com/efloors/'.$product->getSku().'.jpg?width=250&height=250';
$rowNew['price'] = $price;
$rowNew['description'] = $product->getMetaDescription();
$rowNew['accessories_category'] = $product->getResource()->getAttribute('accessories_category')->getFrontend()->getValue($product);
$rowNew['accessories_manufacturer'] = $product->getResource()->getAttribute('accessories_manufacturer')->getFrontend()->getValue($product);
$rowNew['arearugs_colors'] = $product->getResource()->getAttribute('arearugs_colors')->getFrontend()->getValue($product);
$rowNew['arearugs_manufacturer'] = $product->getResource()->getAttribute('arearugs_manufacturer')->getFrontend()->getValue($product);
$rowNew['arearugs_shape'] = $product->getResource()->getAttribute('arearugs_shape')->getFrontend()->getValue($product);
$rowNew['arearugs_theme'] = $product->getResource()->getAttribute('arearugs_theme')->getFrontend()->getValue($product);
$rowNew['bamboo_colors'] = $product->getResource()->getAttribute('bamboo_colors')->getFrontend()->getValue($product);
$rowNew['bamboo_construction'] = $product->getResource()->getAttribute('bamboo_construction')->getFrontend()->getValue($product);
$rowNew['bamboo_installation'] = $product->getResource()->getAttribute('bamboo_installation')->getFrontend()->getValue($product);
$rowNew['bamboo_manufacturer'] = $product->getResource()->getAttribute('bamboo_manufacturer')->getFrontend()->getValue($product);
$rowNew['bamboo_thickness'] = $product->getResource()->getAttribute('bamboo_thickness')->getFrontend()->getValue($product);
$rowNew['bamboo_width'] = $product->getResource()->getAttribute('bamboo_width')->getFrontend()->getValue($product);
$rowNew['carpettile_manufacturer'] = $product->getResource()->getAttribute('carpettile_manufacturer')->getFrontend()->getValue($product);
$rowNew['category_gear'] = $product->getResource()->getAttribute('category_gear')->getFrontend()->getValue($product);
$rowNew['climate'] = $product->getResource()->getAttribute('climate')->getFrontend()->getValue($product);
$rowNew['clocks_finish'] = $product->getResource()->getAttribute('clocks_finish')->getFrontend()->getValue($product);
$rowNew['clocks_material'] = $product->getResource()->getAttribute('clocks_material')->getFrontend()->getValue($product);
$rowNew['clocks_style'] = $product->getResource()->getAttribute('clocks_style')->getFrontend()->getValue($product);
$rowNew['clocks_type'] = $product->getResource()->getAttribute('clocks_type')->getFrontend()->getValue($product);
$rowNew['collar'] = $product->getResource()->getAttribute('collar')->getFrontend()->getValue($product);
$rowNew['cork_colors'] = $product->getResource()->getAttribute('cork_colors')->getFrontend()->getValue($product);
$rowNew['cork_installation'] = $product->getResource()->getAttribute('cork_installation')->getFrontend()->getValue($product);
$rowNew['cork_manufacturer'] = $product->getResource()->getAttribute('cork_manufacturer')->getFrontend()->getValue($product);
$rowNew['cork_thickness'] = $product->getResource()->getAttribute('cork_thickness')->getFrontend()->getValue($product);
$rowNew['cork_width'] = $product->getResource()->getAttribute('cork_width')->getFrontend()->getValue($product);
$rowNew['eco_collection'] = $product->getResource()->getAttribute('eco_collection')->getFrontend()->getValue($product);
$rowNew['erin_recommends'] = $product->getResource()->getAttribute('erin_recommends')->getFrontend()->getValue($product);
$rowNew['format'] = $product->getResource()->getAttribute('format')->getFrontend()->getValue($product);
$rowNew['glasstile_colors'] = $product->getResource()->getAttribute('glasstile_colors')->getFrontend()->getValue($product);
$rowNew['glasstile_composition'] = $product->getResource()->getAttribute('glasstile_composition')->getFrontend()->getValue($product);
$rowNew['glasstile_manufacturer'] = $product->getResource()->getAttribute('glasstile_manufacturer')->getFrontend()->getValue($product);
$rowNew['glasstile_tilesize'] = $product->getResource()->getAttribute('glasstile_tilesize')->getFrontend()->getValue($product);
$rowNew['glasstile_visual'] = $product->getResource()->getAttribute('glasstile_visual')->getFrontend()->getValue($product);
$rowNew['hardwood_colors'] = $product->getResource()->getAttribute('hardwood_colors')->getFrontend()->getValue($product);
$rowNew['hardwood_construction'] = $product->getResource()->getAttribute('hardwood_construction')->getFrontend()->getValue($product);
$rowNew['hardwood_finish'] = $product->getResource()->getAttribute('hardwood_finish')->getFrontend()->getValue($product);
$rowNew['hardwood_manufacturer'] = $product->getResource()->getAttribute('hardwood_manufacturer')->getFrontend()->getValue($product);
$rowNew['hardwood_species'] = $product->getResource()->getAttribute('hardwood_species')->getFrontend()->getValue($product);
$rowNew['hardwood_thickness'] = $product->getResource()->getAttribute('hardwood_thickness')->getFrontend()->getValue($product);
$rowNew['hardwood_width'] = $product->getResource()->getAttribute('hardwood_width')->getFrontend()->getValue($product);
$rowNew['laminate_colors'] = $product->getResource()->getAttribute('laminate_colors')->getFrontend()->getValue($product);
$rowNew['laminate_manufacturer'] = $product->getResource()->getAttribute('laminate_manufacturer')->getFrontend()->getValue($product);
$rowNew['laminate_thickness'] = $product->getResource()->getAttribute('laminate_thickness')->getFrontend()->getValue($product);
$rowNew['laminate_visual'] = $product->getResource()->getAttribute('laminate_visual')->getFrontend()->getValue($product);
$rowNew['luxuryvinyl_colors'] = $product->getResource()->getAttribute('luxuryvinyl_colors')->getFrontend()->getValue($product);
$rowNew['luxuryvinyl_installation'] = $product->getResource()->getAttribute('luxuryvinyl_installation')->getFrontend()->getValue($product);
$rowNew['luxuryvinyl_manufacturer'] = $product->getResource()->getAttribute('luxuryvinyl_manufacturer')->getFrontend()->getValue($product);
$rowNew['luxuryvinyl_tilesize'] = $product->getResource()->getAttribute('luxuryvinyl_tilesize')->getFrontend()->getValue($product);
$rowNew['luxuryvinyl_visual'] = $product->getResource()->getAttribute('luxuryvinyl_visual')->getFrontend()->getValue($product);
$rowNew['new'] = $product->getResource()->getAttribute('new')->getFrontend()->getValue($product);
$rowNew['pattern'] = $product->getResource()->getAttribute('pattern')->getFrontend()->getValue($product);
$rowNew['pcategory'] = $product->getResource()->getAttribute('pcategory')->getFrontend()->getValue($product);
$rowNew['performance_fabric'] = $product->getResource()->getAttribute('performance_fabric')->getFrontend()->getValue($product);
$rowNew['product_type1'] = $product->getResource()->getAttribute('product_type1')->getFrontend()->getValue($product);
$rowNew['sale'] = $product->getResource()->getAttribute('sale')->getFrontend()->getValue($product);
$rowNew['sleeve'] = $product->getResource()->getAttribute('sleeve')->getFrontend()->getValue($product);
$rowNew['styles_name'] = $product->getResource()->getAttribute('styles_name')->getFrontend()->getValue($product);
$rowNew['vacuum_manufacture'] = $product->getResource()->getAttribute('vacuum_manufacture')->getFrontend()->getValue($product);
$rowNew['vacuum_type'] = $product->getResource()->getAttribute('vacuum_type')->getFrontend()->getValue($product);
$rowNew['visual'] = $product->getResource()->getAttribute('visual')->getFrontend()->getValue($product);
$rowNew['pshowprice'] = $product->getData('pshowprice');
$rowNew['pPopularityIndex'] = $product->getData('ppopularityindex');
$rowNew['psell'] = $product->getPsell();
$rowNew['samplesku'] = $product->getSampleSku();



fputcsv($fh, $rowNew);
echo $product->getSku().' saved<br>';
 }
fclose($fh);
exit();

?>



