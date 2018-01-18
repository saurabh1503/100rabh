<?php


namespace TNA\Profile\Controller\Membership;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
	

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
	    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
		

    ) {
        $this->resultPageFactory = $resultPageFactory;
		$this->_customerSession = $customerSession;
		$this->_storeManager = $storeManager;
		$this->_productCollectionFactory = $productCollectionFactory;
		$this->productFactory = $productFactory;		
		                           

        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    
	public function execute()
    {
		
         
		 $productCollection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')
							->addAttributeToFilter( 'status', 1)
							->addAttributeToFilter( 'visibility', 4);

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$objDate = $objectManager->create('Magento\Framework\Stdlib\DateTime\DateTime');
		$currentdate = $objDate->gmtDate();                                    
		$currentdatetime =  strtotime($currentdate);                                                                    
		foreach($productCollection as $Collection){
				    $sku = $Collection->getSku();
				    $productId = $Collection->getId();
					$product = $this->productFactory->create()->load($productId);
					if($product->getEventStartDate()) {
						  $eventStartDate = $product->getEventStartDate();
						  $eventStartDateTime =  strtotime($eventStartDate);
						  if($currentdatetime > $eventStartDateTime ) {
						        $product->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_DISABLED);
                                $product->save();
								echo $product->getSku().'<br>';
                            }
                    }

            }
		 
		 die;
		 
		 $customerId = $this->_customerSession->getCustomer()->getId();
		 if($customerId){
			 return $this->resultPageFactory->create();
		}
		 else {
		  $redirectUrl = $this->_storeManager->getStore()->getUrl('customer/account/login');
		  $resultRedirect = $this->resultRedirectFactory->create();
		  $resultRedirect->setUrl($this->_redirect->success($redirectUrl));
		  return $resultRedirect;
			  
		 }
    }
}
