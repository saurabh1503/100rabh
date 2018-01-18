<?php $_product = $observer->getProduct();
$isSampleOrderEnabled = $observer->getProduct()->getData('enable_sample_order');

if($isSampleOrderEnabled == 1){

    $objectManager = MagentoFrameworkAppObjectManager::getInstance();
    $store = $objectManager->get('MagentoStoreModelStoreManagerInterface')->getStore();
    $_mediapath = BP.'/pub/media/'. 'catalog/product' . $_product->getImage();
    $imageUrl  =  $store->getBaseDir(MagentoFrameworkUrlInterface::URL_TYPE_MEDIA).'catalog/product' . $_product->getImage();

    $getProduct = $objectManager->get('MagentoCatalogModelProduct');
    $sampleSkuExists = $getProduct->getIdBySku($_product->getSku().'-sample');

    $sampleProductPrice = $observer->getProduct()->getData('sample_price');
    if($sampleSkuExists == FALSE) {

        $product = $objectManager->create('MagentoCatalogModelProduct');
        try {
            $product->setWebsiteIds(array(1)) //website ID the product is assigned to, as an array
                    ->setAttributeSetId($_product->getAttributeSetId()) //ID of a attribute set named 'default'
                    ->setTypeId('simple') 
                    ->setCreatedAt(strtotime('now')) 
                    ->setSku($_product->getSku().'-sample') //SKU
                    ->setName($_product->getName()."-sample")
                    ->setWeight(4.0000)
                    ->setStatus(1) 
                    ->setTaxClassId(4) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                    ->setVisibility(1) 
                    ->setPrice($sampleProductPrice) 
                    ->setMetaTitle($_product->getMetaTitle())
                    ->setMetaKeyword($_product->getMetaKeyword())
                    ->setMetaDescription($_product->getMetaDescription())
                    ->setDescription($_product->getDescription())
                    ->setShortDescription($_product->getShortDescription())
                    ->setStockData(
                        array(
                           'use_config_manage_stock' => 0, //'Use config settings' checkbox
                           'manage_stock'=>1, //manage stock
                           'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
                           'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
                           'is_in_stock' => 1, //Stock Availability
                           'qty' => 99999//qty
                        )
                    );

            echo "BEFORE SAVING THE NEW PRODUCT";
            $res = $product->save();
            echo "AFTER SAVING THE NEW PRODUCT";

            var_dump($res); 
            echo $product->getEntityId();
            // Save the new Sample Product ID to original product Attribute. 
        } catch (Exception $e ) {
            echo    $e->getMessage();
        }
    } else {
        echo "UPDATE CODE GOES HERE";
    }
}
?>