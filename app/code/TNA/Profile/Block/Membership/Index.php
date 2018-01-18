<?php
namespace TNA\Profile\Block\Membership;
use Magento\Customer\Model\CustomerFactory;

class Index extends \Magento\Framework\View\Element\Template
{
protected $_customerSession;
protected $customerFactory;
protected $_productCollectionFactory;

	 public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
		\Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory;
		$this->_customerSession = $customerSession;	
		$this->customerFactory = $customerFactory;
		$this->productFactory = $productFactory;		
        parent::__construct($context, $data);
    }
    
	    protected function _prepareLayout()
		{
			$this->pageConfig->getTitle()->set(__('Dues/Membership Product'));
			return parent::_prepareLayout();
		}
	
	
	public function getProductCollection()
{
        $collection = '';	
        $customerId = $this->_customerSession->getCustomerId();
		$customer = $this->customerFactory->create()->load($customerId);
		
		//echo '<pre>';print_r($customer->getData());die;
		/*if($customer->getAllianceDesignations()){
		$alliance_designations = $customer->getAllianceDesignations();
		}
		else {
		$alliance_designations = '';
		}

		//echo '--'.$alliance_designations = $customer->getAllianceDesignations();
		if($alliance_designations != '' ) { 
		$designation = array();
		$productSku = array();
		
		$designation = explode(',' , $alliance_designations);
		foreach($designation as $arr){
		 switch($arr){
		 case 'CIC':array_push($productSku,'due_cic');continue;
		 case 'CISR':array_push($productSku,'due_cisr');continue;
		 case 'CRM':array_push($productSku,'due_crm');continue;
		 case 'CSRM':array_push($productSku,'due_csrm');continue;
		 }
		}
	    $newstr = implode(',', $productSku);
		$collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToFilter( 'sku', array('in' => $newstr));
		}*/
		$productCollection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToFilter( 'attribute_set_id', 19);

		$skuArray = array();
		foreach($productCollection as $Collection){
			$skuArray[] = $Collection->getSku();
		}
		$skuofDues = array();
		if(count($skuArray) > 0){
			$currentYear = date("Y");
			
			foreach($skuArray as $sku){
			
			$numbers = preg_replace('/[^0-9]/', '', $sku);
			
				if($numbers == $currentYear){
					$skuofDues[] = $sku;
				}
			//echo '<pre>';print_r($skuofDues);
			$collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToFilter( 'sku', array('in' => $skuofDues));
			}
		}
		return $collection;
}
	public function getAddToCartPostParams($product)
	{
		return $this->listProductBlock->getAddToCartPostParams($product);
	}
	
    
	public function getMembershipProductDetail(){
		return $productData = $this->productFactory->create();
	}
	
	public function getMembershipProductData(){
		 $product = $this->productFactory->create()->loadByAttribute('sku', 'membership_academy');
		 return $product->getEntityId();
		 
	}
	
	
}



