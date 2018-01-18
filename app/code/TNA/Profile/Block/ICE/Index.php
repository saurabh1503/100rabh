<?php
namespace TNA\Profile\Block\ICE;
use Magento\Customer\Model\CustomerFactory;
class Index extends \Magento\Framework\View\Element\Template
{
	protected $_customerSession;
	private $iceFactory;
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,   
		\Magento\Customer\Model\Session $customerSession,
		\TNA\Profile\Model\ICEFactory $iceFactory,
		array $data = []
    )
    {  
	    $this->_customerSession = $customerSession;
		$this->iceFactory = $iceFactory;
		parent::__construct($context, $data);
    }
	 public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
     public function geticeFormData()
    {
       $customerId = $this->_customerSession->getCustomerId();
	   $iceForm = $iceFactory = $this->iceFactory->create()->load($customerId,'customer_id');
	   return $iceForm; 
	   
    }

}
