<?php
namespace TNA\Profile\Controller\ICE;

class Save extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $_customerSession;
	private $scopeConfig;
	private $iceFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Framework\App\RequestInterface $request,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\TNA\Profile\Model\ICEFactory $iceFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
		$this->_customerSession = $customerSession;
		$this->_request = $request;
		$this->scopeConfig = $scopeConfig;
		$this->iceFactory = $iceFactory;
        parent::__construct($context);
    }

    
    public function execute()
    {
		$data = $this->_request->getParams();
		
		 if (!$data) {
			$this->_redirect('*/*/');
			return;
		 }
		 if($data){
		    $customerId = $this->_customerSession->getCustomer()->getId();
			$data['customer_id'] = $customerId;
			
		     try 
			 {
			     $iceFactory = $this->iceFactory->create();
				 $iceFactory->setData($data);
				 $iceFactory->save();
				 $this->messageManager->addSuccess(__('Your data have been saved successfully.'));
				 $resultRedirect = $this->resultRedirectFactory->create();
				 $this->_redirect('customer/ice/index'); 
			 }
		     catch (\Exception $e) {
			 //echo '<pre>';print_r($e);die;
			 $this->messageManager->addError(__('We can\’t process your request right now.')
	         );
			 $this->_redirect('*/*/');
			 }
	     }
		
   }	
}