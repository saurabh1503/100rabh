<?php
namespace Efloor\Offlinechat\Block;
 
class Offlinechat extends \Magento\Framework\View\Element\Template
{
public function getRequestQuotationTxt()
    {
        return 'Hello world!';
    }
 public function getFormAction()
    {
		
        return $this->getUrl('offlinechat/index/post', ['_secure' => true]);
    }
	
	public function getFormData() {
		$finalProductArray = array();
		$values = $this->getRequest()->getParams();
		if(!empty($values)){
		//echo "<pre>"; print_r($values);  die(); 
		}
		
		
		
	}
	
	
	
}

