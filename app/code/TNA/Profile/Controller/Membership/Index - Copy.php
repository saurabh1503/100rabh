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
		\Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
		$this->_customerSession = $customerSession;
		$this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    
	public function execute()
    {
		
		$poductReource=$this->_attributeLoading->create();
        $attr = $poductReource->getAttribute('license_type');
        $option_id ='';
		if ($attr->usesSource()) {
                  $option_id = $attr->getSource()->getOptionId('Online Self-Paced');
         }
         
		 $productCollection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')
							->addAttributeToFilter( 'status', 1)
							->addAttributeToFilter( 'visibility', 4)
							->addAttributeToFilter( 'learning_options', $option_id);
							
		foreach($productCollection as $Collection){
				    $sku = $Collection->getSku();
				    $productId = $Collection->getId();
					$product = $this->productFactory->create()->load($productId);
					if($product->getEventEndDate()) {
					    $year = '';
						$nextMonth = '';
						$d = '';
						$eventEndtDate = $product->getEventEndDate();
						$date = new DateTime($eventEndtDate);
						$year = $date->format('Y');
						$month = $date->format('m');
                        
						if($month == 12) {
							$year = $year + 1; 
							$nextMonth =  '01';
						}
						else {					
							$nextMonth = $month + 1;
					    }
						if(strlen($nextMonth) > 1 ) {
							$nextMonth = $nextMonth;
						}
						else {
							$nextMonth = '0'.$nextMonth;
						}
						
						$d = cal_days_in_month(CAL_GREGORIAN,$nextMonth,$year);
                        $event_end_date = $year.'-'.$nextMonth.'-'.$d.' 00:00:00';
						
						$product->setEventEndDate($event_end_date);
						//$product->save();
						echo $product->getSku().'<br>';
                          
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
