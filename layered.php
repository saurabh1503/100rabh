<?php
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();

$filterableAttributes = $objectManager->get(\Magento\Catalog\Model\Layer\Category\FilterableAttributeList::class);

$appState = $objectManager->get(\Magento\Framework\App\State::class);
$layerResolver = $objectManager->get(\Magento\Catalog\Model\Layer\Resolver::class);
$filterList = $objectManager->create(
    \Magento\Catalog\Model\Layer\FilterList::class,
    [
        'filterableAttributes' => $filterableAttributes
    ]
);

 $category = 7;
 
 $appState->setAreaCode('frontend');
 $layer = $layerResolver->get();
 $layer->setCurrentCategory($category);
 $filters = $filterList->getFilters($layer);
 
$object_manager = $objectManager->create('Magento\Catalog\Model\Category')->load($category);
$url=$object_manager->getUrl();

 
 // foreach ($filters as $filter) {
    // if ($filter->getItemsCount()) {
        // $filters[] = $filter;
		// $name = $filter->getName();
	// //echo $name." ";
	
	// foreach ($filter->getItems() as $item) {
	
		// $value = $item->getLabel();
		// $val=$item->getValue();
		// //echo $value." ";
		// echo $url.'/?'.$name.'='.$val;
	// }
    // }
	
// }

?>

    <div class="block filter" id="layered-filter-block" data-mage-init='{"collapsible":{"openedState": "active", "collapsible": true, "active": false, "collateral": { "openedState": "filter-active", "element": "body" } }}'>
       
        <div class="block-title filter-title">
            <strong data-role="title">Shop By</strong>
        </div>
        <div class="block-content filter-content">

           
            <?php foreach ($filters as $filter): ?>
                <?php if ($filter->getItemsCount()): ?>
                   
                        <strong role="heading" aria-level="2" class="block-subtitle filter-subtitle"><?php /* @escapeNotVerified */ echo __('Shopping Options') ?></strong>
                        <div class="filter-options" id="narrow-by-list" data-role="content" data-mage-init='{"accordion":{"openedState": "active", "collapsible": true, "active": false, "multipleCollapsible": false}}'>
                    
                    <div data-role="collapsible" class="filter-options-item">
                        <div data-role="title" class="filter-options-title"><?php /* @escapeNotVerified */ echo $filter->getName(); ?></div>
                        <?php foreach ($filter->getItems() as $item): ?>
						<div data-role="content" class="filter-options-content"><a href="<?php echo $url.'/?'.strtolower($filter->getName()).'='.$item->getValue(); ?>"><?php /* @escapeNotVerified */ echo $item->getLabel(); ?></a></div>
                     <?php endforeach; ?>
					</div>
                <?php endif; ?>
            <?php endforeach; ?>
          
                </div>
            
        </div>
    </div>










