<?php
namespace Efloor\Offlinechat\Block;
 
class Requestdata extends \Magento\Framework\View\Element\Template
{
public function getTest()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$coreRegistry = $objectManager->get('\Magento\Framework\Registry');
       $data =  $coreRegistry->registry('data_test');
	   
	   print_r($data); die('In block');
	   
	   
    }
	
	}