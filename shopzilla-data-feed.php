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
			->addAttributeToFilter('pproducttypeid', array('neq' => 11))
			->addAttributeToFilter('pproducttypeid', array('neq' => 1))
			->addAttributeToFilter('pshowprice', array('eq' => 1))
			->addAttributeToFilter('status', 1)
			->addAttributeToFilter('name', array('nlike' => 'Karndean'))
			->addAttributeToSort('created_at', 'ASC')
			->setPageSize(100)
            ->load();

$filePath = 'shopzilla-data-feed.csv';
if (file_exists($filePath)) {
    $fh = fopen($filePath, 'a');     
} else {
    $fh = fopen($filePath, 'w');
}  

$header1 = array('Unique ID','Category','Brand','Title','Description','Product URL','Image URL','Availability','Condition','Ship Weight','Ship Cost','Current Price');
 
fputcsv($fh, $header1); 

foreach($collection as $product){
		$specialPrice = $product->getSpecialPrice();
		if($specialPrice){
		$price = $specialPrice;
		} 
		else{
			$price = $product->getPrice();
		}
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
$rowNew['Unique ID'] = $product->getSku();
$rowNew['Category'] = GetCategoryID($product->getData('pproducttypeid'),$rowNew['Product Title']);
$rowNew['Brand'] = $categoryName;
$rowNew['Title'] = $product->getName();
$rowNew['Description'] = $product->getMetaDescription();
$rowNew['Product URL'] = $product->getProductUrl();
$rowNew['Image URL'] = $imgurl.$product->getImage();
$rowNew['Availability'] = $stock;
$rowNew['condition'] = "New";
$rowNew['Ship Weight'] = $product->getWeight();
$rowNew['Ship Cost'] = "";
$rowNew['Current Price'] = $price;

fputcsv($fh, $rowNew);

 }

fclose($fh);
exit();

function GetCategoryID($productTypeID, $name)
{
    $NameToLower = strtolower(trim($name));
    switch ($productTypeID)
    {
        case 1: // Area Rug
            return "Home & Garden / Decor / Rugs";
			break;
        case 2: // Laminate
        case 3: // Hardwood
        case 4: // Bamboo
        case 5: // Cork
        case 6: // LVT
        case 7: // Carpet Tile
        case 9: // Tile
            return "Home & Garden / Hardware / Building Materials / Flooring";
			break;
        case 8: // Accessories
            if (strpos($NameToLower,"pillow")){
                return "Home & Garden / Decor / Throw Pillows";
			}
			break;
            if (strpos($NameToLower,"adhesive")){
                return "Home & Garden / Hardware / Adhesives, Coatings & Sealants";
			}
			break;
            if (strpos($NameToLower,"grout")){
                return "Home & Garden / Hardware / Building Materials";
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
                || NameToLower.EndsWith("long")){
                return "Home & Garden / Hardware / Building Materials / Molding";
				}
				break;
            if (strpos($NameToLower,"mosaic") || (strpos($NameToLower,"\"") && strpos($NameToLower," x "))){
                return "Home & Garden / Hardware / Building Materials / Tile";
			}
			break;
            if (strpos($NameToLower,"vent")){
                return "Home & Garden / Hardware / Tools, Hardware & Accessories / Hardware Accessories / Vents & Flues";
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
                || strpos($NameToLower,"maintenance kit")
                || strpos($NameToLower,"care kit")
                || strpos($NameToLower,"care system")){
                return "Home & Garden / Household Supplies / Household, Laundry & Shoe Supplies / Household Cleaning Supplies / Household Cleaning Products";
				}
				break;
            if (strpos($NameToLower,"soap spray")){
                return
                    "Home & Garden / Household Supplies / Household, Laundry & Shoe Supplies / Household Cleaning Supplies / Household Cleaning Products / Hardwood Floor Cleaners";
			}
			break;
            if (NameToLower.StartsWith("capture")
                || strpos($NameToLower,"tech final answer")
                || strpos($NameToLower,"perky spotter")){
                return
                    "Home & Garden / Household Supplies / Household, Laundry & Shoe Supplies / Household Cleaning Supplies / Household Cleaning Products";
				}
				break;
            if (strpos($NameToLower," pad ") || NameToLower.EndsWith(" pad")){
                return
                    "Home & Garden / Household Supplies / Household, Laundry & Shoe Supplies / Household Cleaning Supplies / Mop Heads & Refills";
			}
			break;
            if (strpos($NameToLower," mop ") || NameToLower.EndsWith(" mop")){
                return
                    "Home & Garden / Household Supplies / Household, Laundry & Shoe Supplies / Household Cleaning Supplies / Mops";
			}
			break;
            if (strpos($NameToLower,"underlayment")){
                return "Home & Garden / Hardware / Building Materials / Flooring";
			}
			break;
            if (strpos($NameToLower,"chair mat")){
                return "Home & Garden / Decor / Chair & Sofa Cushions";
			}
			break;
            if (strpos($NameToLower,"stripper")){
                return "Home & Garden / Hardware / Building Materials";
			}
			break;
            if (strpos($NameToLower,"floor protectors")){
                return "Home & Garden / Household Supplies";
			}
			break;
            if (strpos($NameToLower,"scotchguard") || strpos($NameToLower,"stain protection")){
                return
                    "Home & Garden / Household Supplies / Household, Laundry & Shoe Supplies / Household Cleaning Supplies / Fabric & Upholstery Protectors";
			}
			break;
            if (strpos($NameToLower,"vaparrest") || strpos($NameToLower,"sealant") ||
                strpos($NameToLower,"putty") || strpos($NameToLower,"filler")){
                return "Home & Garden / Hardware / Adhesives, Coatings & Sealants";
				}
				break;
            if (strpos($NameToLower,"mortar")){
                return "Home & Garden / Hardware / Building Materials";
			}
			break;
            if (strpos($NameToLower,"schluter ditra")){
                return "Home & Garden / Hardware / Building Materials / Flooring";
			}
		break;
          
        case 10: // Clocks
            if (strpos($NameToLower,"desk ") || strpos($NameToLower,"shelf ")){
                return "Home & Garden / Decor / Clocks / Desk & Shelf Clocks";
			}
			break;
            if (strpos($NameToLower,"wall ")){
                return "Home & Garden / Decor / Clocks / Wall Clocks";
			}
			break;
            if (strpos($NameToLower,"floor ") || strpos($NameToLower,"grandfather ")){
                return "Home & Garden / Decor / Clocks / Floor & Grandfather Clocks ";
			}
			break;
            if (strpos($NameToLower,"alarm ")){
                return "Home & Garden / Decor / Clocks / Alarm Clocks";
            return "Home & Garden / Decor / Clocks";
			}
			break;
        case 11: // Carpet
            return "Home & Garden / Hardware / Building Materials / Carpets";
			break;
        case 12: // Vacuums
            return "Home & Garden / Appliances & Accessories / Household Appliances / Vacuums";
			break;
    }
}


$fp = fopen('shopzilla-data-feed.csv','w') or die ('No file!!!');
fputcsv($fp,$csv);
fclose($fp);
rename('shopzilla-data-feed.csv','shopzilla-data-feed.txt');
	
?>



