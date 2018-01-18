<?php
use Magento\Framework\App\Bootstrap;
include('../app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$state->setAreaCode('frontend');


$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
$categoryCollection = $objectManager->get('\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory');
$StockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
$collection = $productCollection->create()
            ->addAttributeToSelect('*')
            ->setPageSize(10)
            ->load();

$filePath = 'feed.csv';
if (file_exists($filePath)) {
    $fh = fopen($filePath, 'a');     
} else {
    $fh = fopen($filePath, 'w');
}  

$header1 = array('id','title','description','link','price','brand','mpn','condition','image_link','google_product_category','product_type','availability','shipping_weight','shipping');
 
fputcsv($fh, $header1); 

foreach($collection as $product){ 
//echo "<pre>"; print_r($product->getData()); die();
	$ids= $product->getCategoryIds();
	$categories = $categoryCollection->create()
                                 ->addAttributeToSelect('*')
                                 ->addAttributeToFilter('entity_id', $ids);
	foreach($categories as $category){
		$categoryName= $category->getName();

	}
$avail= $StockState->getStockQty($product->getId(), $product->getStore()->getWebsiteId());
if($avail>=1){	
	$stock="In Stock";
}
else{
	$stock="Out of Stock";

}
$rowNew = array();

$rowNew['id'] = $product->getId();
$rowNew['title'] = $product->getName();
$rowNew['description'] = $product->getDescription();
$rowNew['link'] = $product->getProductUrl();
$rowNew['price'] = $product->getPrice();
$rowNew['brand'] = $categoryName;
$rowNew['mpn'] = $product->getManufacturerSku();
$rowNew['condition'] = $product->getName();
$rowNew['image_link'] = $product->getImage();
$rowNew['google_product_category'] = GetGoogleProductCategory($product->getData('pproducttypeid'),$rowNew['title']);
$rowNew['product_type'] = $product->getData('pproducttypeid');
$rowNew['availability'] = $stock;
$rowNew['shipping_weight'] = $product->getShipping();
$rowNew['shipping'] = $product->getWeight();

fputcsv($fh, $rowNew);


 }

fclose($fh);
exit();

function GetGoogleProductCategory($productTypeID, $name)
{
    $NameToLower = strtolower(trim($name));
    switch ($productTypeID)
    {
        case 1: //Area rugs
            return "598"; //"Home & Garden > Decor > Rugs"
			break;
        case 2: //Laminate
        case 3: //Hardwood
        case 4: //Bamboo
        case 5: //Cork
        case 6: //VCT
        case 7: //Carpet Tile
        case 9: //Glass tile
            return "2826";//"Hardware > Building Materials > Flooring & Carpet"
			break;
        case 8: //Accessories
            if (strpos($NameToLower,"pillow")){
                return "4454"; //"Home & Garden > Decor > Throw Pillows"
}
			break;
            if (strpos($NameToLower,"adhesive")){
                return "503742"; //"Hardware > Building Consumables > Hardware Glue & Adhesives"
	}
			break;
            if (strpos($NameToLower,"grout")){
                return "499876"; //"Hardware > Building Consumables > Masonry Consumables > Grout"
	}
			break;
            if (strpos($NameToLower,"wall base")
                     || strpos($NameToLower,"bullnose")
                     || strpos($NameToLower,"stair nose")
                     || strpos($NameToLower,"square nose")
                     || strpos($NameToLower,"t-mold")
                     || strpos($NameToLower,"reducer")
                     || strpos($NameToLower,"quarter round")
                     || strpos($NameToLower,"cove base")
                     || strpos($NameToLower,"threshold")
                     || strpos($NameToLower,"long")){
	return "7112"; //"Hardware > Building Materials > Molding"
	}
				break;
            if (strpos($NameToLower,"mosaic") || (strpos($NameToLower,"\"") && strpos($NameToLower," x "))){
                return "7136"; //"Hardware > Building Materials > Wall & Ceiling Tile"
			}
			break;
	}
}	
?>



