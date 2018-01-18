<?php


namespace TNA\Profile\Controller\Notifications;

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
