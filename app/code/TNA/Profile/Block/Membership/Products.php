<?php
namespace TNA\Profile\Block\Membership;
use Magento\Customer\Model\CustomerFactory;
use Magento\Catalog\Block\Product\ListProduct;	
class Products extends \Magento\Framework\View\Element\Template
{
protected $_customerSession;
protected $customerFactory;
protected $_productCollectionFactory;

	 public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
		\Magento\Catalog\Block\Product\ListProduct $listProductBlock,
        array $data = []
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory;
		$this->listProductBlock = $listProductBlock;
		$this->_customerSession = $customerSession;	
		$this->customerFactory = $customerFactory;		
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
		$alliance_designations = $customer->getAllianceDesignations();
		if($alliance_designations) {
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
		}
		
    return $collection;
}
	public function getAddToCartPostParams($product)
	{
		return $this->listProductBlock->getAddToCartPostParams($product);
	}
	
    
}

