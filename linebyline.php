<?php 
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
$state->setAreaCode('frontend');

$date = date('Y-m-d H:i:s');
$fromDate = date('Y-m-d H:i:s', strtotime($date .' -200 day'));

$productCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
$collection = 	$productCollection->addAttributeToSelect('sku')
								->addFieldToFilter('created_at', array('from'=>$fromDate, 'to'=>$date))
								->load();

$filename = "nextopia-feed.csv";
$csv = ImportCSV2Array($filename);

$csvData = file_get_contents($filename);
$csvs = explode("\n", $csvData);

$newData = array();
foreach($csv as $rowData) {
	// $modskus = array(59928,59927,10416);
	// foreach($modskus as $sku){
    // if($rowData['sku']== $sku){
    // $rowData['price']=55;
	// }
	// }
	foreach ($collection as $product){
     $sku = $product->getSku();
	 if($rowData['sku']== $sku){
		$rowData['price']=55;
	}
	 
}
	
    $newData[] = implode(",", $rowData);
	
}
array_unshift($newData,$csvs[0]);
$newData = implode("\n", $newData);
file_put_contents($filename, $newData);

function ImportCSV2Array($filename)
{
    $row = 0;
    $col = 0;
 
    $handle = @fopen($filename, "r");
    if ($handle) 
    {
        while (($row = fgetcsv($handle, 4096)) !== false) 
        {
            if (empty($fields)) 
            {
                $fields = $row;
                continue;
            }
 
            foreach ($row as $k=>$value) 
            {
                $results[$col][$fields[$k]] = $value;
            }
            $col++;
            unset($row);
        }
        if (!feof($handle)) 
        {
            echo "Error: unexpected fgets() failn";
        }
        fclose($handle);

    }
 
    return $results;
}

?>