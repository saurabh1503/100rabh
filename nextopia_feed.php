<?php
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
ini_set('max_execution_time', -1);
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$state->setAreaCode('frontend');

$date = date('Y-m-d H:i:s');
$fromDate = date('Y-m-d H:i:s', strtotime($date .' -1 day'));
$imgurl= $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product';
$products = $objectManager->get('Magento\Catalog\Model\ProductFactory');
$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
$productCollection->addAttributeToSelect('name')
			->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
			->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
			->addFieldToFilter('created_at', array('from'=>$fromDate, 'to'=>$date))
			//->addAttributeToFilter('entity_id', array('gt' => 120144))
			//->setPageSize(50000)
			->load();

$filePath = 'nextopia-feed.csv';
  

$header1 = array('id','sku','title','link','image_link','price','description','accessories_category','accessories_manufacturer','arearugs_colors','arearugs_manufacturer','arearugs_shape','arearugs_theme','bamboo_colors','bamboo_construction','bamboo_installation','bamboo_manufacturer','bamboo_thickness','bamboo_width','carpettile_manufacturer','category_gear','climate','clocks_finish','clocks_material','clocks_style','clocks_type','collar','cork_colors','cork_installation','cork_manufacturer','cork_thickness','cork_width','eco_collection','erin_recommends','format','glasstile_colors','glasstile_composition','glasstile_manufacturer','glasstile_tilesize','glasstile_visual','hardwood_colors','hardwood_construction','hardwood_finish','hardwood_manufacturer','hardwood_species','hardwood_thickness','hardwood_width','laminate_colors','laminate_manufacturer','laminate_thickness','laminate_visual','luxuryvinyl_colors','luxuryvinyl_installation','luxuryvinyl_manufacturer','luxuryvinyl_tilesize','luxuryvinyl_visual','new','pattern','pcategory','performance_fabric','product_type1','sale','sleeve','styles_name','vacuum_manufacture','vacuum_type','visual','pshowprice','pPopularityIndex','psell','samplesku');


 if (file_exists($filePath)) {
    $fh = fopen($filePath, 'a');     
} else {
	
    $fh = fopen($filePath, 'w');
	fputcsv($fh, $header1);
}
 

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
					$rowNew['id'] = $product->getId();
					$rowNew['sku'] = $product->getSku();
					$rowNew['title'] = $product->getName();
					$rowNew['link'] = $product->getProductUrl();
					$rowNew['image_link'] = 'https://www.gowebpix.com/efloors/'.$product->getSku().'.jpg?width=250&height=250';
					$rowNew['price'] = $price;
					$rowNew['description'] = $product->getMetaDescription();
					$rowNew['accessories_category'] = $product->getAccessoriesCategory();
					$rowNew['accessories_manufacturer'] = $product->getAccessoriesManufacturer();
					$rowNew['arearugs_colors'] = $product->getArearugsColors();
					$rowNew['arearugs_manufacturer'] = $product->getArearugsManufacturer();
					$rowNew['arearugs_shape'] = $product->getArearugsShape();
					$rowNew['arearugs_theme'] = $product->getArearugsTheme();
					$rowNew['bamboo_colors'] = $product->getBambooColors();
					$rowNew['bamboo_construction'] = $product->getBambooConstruction();
					$rowNew['bamboo_installation'] = $product->getBambooInstallation();
					$rowNew['bamboo_manufacturer'] = $product->getBambooManufacturer();
					$rowNew['bamboo_thickness'] = $product->getBambooThickness();
					$rowNew['bamboo_width'] = $product->getBambooWidth();
					$rowNew['carpettile_manufacturer'] = $product->getCarpettileManufacturer();
					$rowNew['category_gear'] = $product->getCategoryGear();
					$rowNew['climate'] = $product->getClimate();
					$rowNew['clocks_finish'] = $product->getClocksFinish();
					$rowNew['clocks_material'] = $product->getClocksMaterial();
					$rowNew['clocks_style'] = $product->getClocksStyle();
					$rowNew['clocks_type'] = $product->getClocksType();
					$rowNew['collar'] = $product->getCollar();
					$rowNew['cork_colors'] = $product->getCorkColors();
					$rowNew['cork_installation'] = $product->getCorkInstallation();
					$rowNew['cork_manufacturer'] = $product->getCorkManufacturer();
					$rowNew['cork_thickness'] = $product->getCorkThickness();
					$rowNew['cork_width'] = $product->getCorkWidth();
					$rowNew['eco_collection'] = $product->getEcoCollection();
					$rowNew['erin_recommends'] = $product->getErinRecommends();
					$rowNew['format'] = $product->getFormat();
					$rowNew['glasstile_colors'] = $product->getGlasstileColors();
					$rowNew['glasstile_composition'] = $product->getGlasstileComposition();
					$rowNew['glasstile_manufacturer'] = $product->getGlasstileManufacturer();
					$rowNew['glasstile_tilesize'] = $product->getGlasstileTilesize();
					$rowNew['glasstile_visual'] = $product->getGlasstileVisual();
					$rowNew['hardwood_colors'] = $product->getHardwoodColors();
					$rowNew['hardwood_construction'] = $product->getHardwoodConstruction();
					$rowNew['hardwood_finish'] = $product->getHardwoodFinish();
					$rowNew['hardwood_manufacturer'] = $product->getHardwoodManufacturer();
					$rowNew['hardwood_species'] = $product->getHardwoodSpecies();
					$rowNew['hardwood_thickness'] = $product->getHardwoodThickness();
					$rowNew['hardwood_width'] = $product->getHardwoodWidth();
					$rowNew['laminate_colors'] = $product->getLaminateColors();
					$rowNew['laminate_manufacturer'] = $product->getLaminateManufacturer();
					$rowNew['laminate_thickness'] = $product->getLaminateThickness();
					$rowNew['laminate_visual'] = $product->getLaminateVisual();
					$rowNew['luxuryvinyl_colors'] = $product->getLuxuryvinylColors();
					$rowNew['luxuryvinyl_installation'] = $product->getLuxuryvinylInstallation();
					$rowNew['luxuryvinyl_manufacturer'] = $product->getLuxuryvinylManufacturer();
					$rowNew['luxuryvinyl_tilesize'] = $product->getLuxuryvinylTilesize();
					$rowNew['luxuryvinyl_visual'] = $product->getLuxuryvinylVisual();
					$rowNew['new'] = $product->getNew();
					$rowNew['pattern'] = $product->getPattern();
					$rowNew['pcategory'] = $product->getPcategory();
					$rowNew['performance_fabric'] = $product->getPerformanceFabric();
					$rowNew['product_type1'] = $product->getProductType1();
					$rowNew['sale'] = $product->getSale();
					$rowNew['sleeve'] = $product->getSleeve();
					$rowNew['styles_name'] = $product->getStylesName();
					$rowNew['vacuum_manufacture'] = $product->getVacuumManufacture();
					$rowNew['vacuum_type'] = $product->getVacuumType();
					$rowNew['visual'] = $product->getVisual();
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



