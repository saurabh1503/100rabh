<?php
namespace Fcamodule\Offline\Block;
 
class Offline extends \Magento\Framework\View\Element\Template
{

 public function getFormAction()
    {
		
        return $this->getUrl('offline/index/post', ['_secure' => true]);
    }
	
	public function getFormData() {
		$finalProductArray = array();
		$values = $this->getRequest()->getParams();
		if(!empty($values)){
		//echo "<pre>"; print_r($values);  die(); 
		}
		
		
		
	}
	
	
	
}

