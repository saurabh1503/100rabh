<?php
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);
ini_set("memory_limit", "-1");
set_time_limit(0);
error_reporting(E_ALL);
require 'app/bootstrap.php';
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$objectManager1 = \Magento\Framework\App\ObjectManager::getInstance();
$registry = $objectManager1->get('\Magento\Framework\Registry');
$registry->register('isSecureArea', true);
$category_id = '50';
$collection = $objectManager1 ->get('Magento\Catalog\Model\Category')->load($category_id);
$collection->delete();

?>
