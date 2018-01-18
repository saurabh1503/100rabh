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
$imgurl= $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product';
$collection = $productCollection->create()
            ->addAttributeToSelect('*')
			// ->addAttributeToSort('created_at', 'ASC')
            ->setPageSize(10)
            ->load();

$filePath = 'google-data-feed.csv';
if (file_exists($filePath)) {
    $fh = fopen($filePath, 'a');     
} else {
    $fh = fopen($filePath, 'w');
}  

$header1 = array('id','title','description','link','price','unit_pricing_measure','brand','mpn','condition','image_link','google_product_category','product_type','availability','shipping_weight','shipping','gtin','identifier_exists');
 
fputcsv($fh, $header1); 

foreach($collection as $product){
	$SfPerQty=$product->getData('psfperqty');
	$UnitPricingMeasure = ($SfPerQty == 0 ? "" : "1 sq ft");
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

$rowNew['id'] = $product->getSku();
$rowNew['title'] = $product->getName();
$rowNew['description'] = $product->getDescription();
$rowNew['link'] = $product->getProductUrl();
$rowNew['price'] = $product->getPrice();
$rowNew['unit_pricing_measure'] =$UnitPricingMeasure;
$rowNew['brand'] = $categoryName;
$rowNew['mpn'] = $product->getManufacturerSku();
$rowNew['condition'] = "New";
$rowNew['image_link'] = $imgurl.$product->getImage();
$rowNew['google_product_category'] = GetGoogleProductCategory($product->getData('pproducttypeid'),$rowNew['title']);
$rowNew['product_type'] = $product->getData('pproducttypeid');
$rowNew['availability'] = $stock;
$rowNew['shipping_weight'] = $product->getWeight();
$rowNew['shipping'] = "";
$rowNew['gtin'] = "";
$rowNew['identifier_exists'] = "";

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
			
			if (strpos($NameToLower,"vent")){
                return "2766"; //"Hardware > Heating, Ventilation & Air Conditioning > Vents & Flues"
			}
			break;
            if (strpos($NameToLower,"clean")
                     || strpos($NameToLower,"polish")
                     || strpos($NameToLower,"remover")
                     || strpos($NameToLower,"sealer")
                     || strpos($NameToLower,"once n done")
                     || strpos($NameToLower,"once 'n done")
                     || strpos($NameToLower,"new beginning")
                     || strpos($NameToLower,"finish")
                     || strpos($NameToLower,"capture")
                     || strpos($NameToLower,"maintenance kit")
                     || strpos($NameToLower,"care kit")
                     || strpos($NameToLower,"care system")
                     || strpos($NameToLower,"tech final answer")
                     || strpos($NameToLower,"perky spotter")
                     || strpos($NameToLower,"soap spray")){
                return "4977"; //"Home & Garden > Household Supplies > Household Cleaning Supplies > Household Cleaning Products > Floor Cleaners"
				}
			break;
            if (strpos($NameToLower," pad ") || strpos($NameToLower," pad")){
                return "6264"; //"Home & Garden > Household Supplies > Household Cleaning Supplies > Mop Heads & Refills"
				}
			break;
            if (strpos($NameToLower," mop ") || strpos($NameToLower," mop")){
                return "2713"; //"Home & Garden > Household Supplies > Household Cleaning Supplies > Mops"
			}
			break;
            if (strpos($NameToLower,"underlayment")){
                return "2826"; //"Hardware > Building Materials > Flooring & Carpet"
				}
			break;
            if (strpos($NameToLower,"chair mat")){
                return "6521"; //"Office Supplies > Office & Chair Mats > Chair Mats"
				}
			break;
            if (strpos($NameToLower,"stripper")){
                return "503741";// Hardware Building Consumables      Solvents, Strippers & Thinners
				}
			break;
            if (strpos($NameToLower,"floor protectors")){
                return "7214"; //  Home & Garden    Household Supplies        Furniture Floor Protectors
				}
			break;
            if (strpos($NameToLower,"scotchguard") || strpos($NameToLower,"stain protection")){
                return "6419"; //  Home & Garden    Household Supplies        Household Cleaning Supplies         Fabric & Upholstery Protectors
				}
			break;
            if (strpos($NameToLower,"vaparrest") || strpos($NameToLower,"sealant") || strpos($NameToLower,"putty") || strpos($NameToLower,"filler")){
                return "503744"; //         Hardware Building Consumables         Protective Coatings & Sealants
				}
			break;
            if (strpos($NameToLower,"mortar")){
                return "2282"; //  Hardware Building Consumables      Masonry Consumables         Cement, Mortar & Concrete Mixes
				}
			break;
            if (strpos($NameToLower,"schluter ditra")){
                return "528858"; //         Radiant Floor Heating Supplies
}
			break;
            // return "";
			// break;
        case 10: //Clocks
            if (strpos($NameToLower,"desk ") || strpos($NameToLower,"shelf ")){
                return "6912"; //"Home & Garden > Decor > Clocks > Desk & Shelf Clocks"
				}
			break;
            if (strpos($NameToLower,"wall ")){
                return "3840"; //"Home & Garden > Decor > Clocks > Wall Clocks"
				}
			break;
            if (strpos($NameToLower,"floor ") || strpos($NameToLower,"grandfather ")){
                return "3696"; //"Home & Garden > Decor > Clocks > Floor & Grandfather Clocks"
				}
			break;
            if (strpos($NameToLower,"alarm ")){
                return "4546"; //"Home & Garden > Decor > Clocks > Alarm Clocks"
				}
			break;
            //return "3890"; //"Home & Garden > Decor > Clocks"
        case 12:
            return "619"; //       Home & Garden    Household Appliances      Vacuums
			break;
			// default:
            // return "N/A"; //       Home & Garden    Household Appliances      Vacuums
			// break;


	}
}

	
?>



