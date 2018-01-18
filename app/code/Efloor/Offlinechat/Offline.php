<?php
namespace Efloor\Requestquote\Controller\Index;


class Offline extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;


    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return void
     */
    public function execute()
    {

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        try{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$post = (array) $this->getRequest()->getPost();
		$postObject = new \Magento\Framework\DataObject();
        $postObject->setData($post);
           

        if (!empty($post)) {

            $cfname = $post['contactFirstName'];
			$clname = $post['contactLastName'];
			$cemail = $post['emailaddress'];
			$cgroups = $post['group1'];
			$ccomment = $post['contactComment'];
			$ccomment = $post['Referrer'];
		
		
			$transportBuilder = $objectManager->create('\Magento\Framework\Mail\Template\TransportBuilder');
				$transport = $transportBuilder
                ->setTemplateIdentifier(23)
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => $postObject])
                ->setFrom(['name' => $cfname,'email' => $cemail])
                ->addTo('info@efloors.com')
                ->getTransport();
            $transport->sendMessage();
		
            $this->resultPageFactory->create();
			$this->messageManager->addSuccessMessage('Your chat request is submitted successfully.');
            return $resultRedirect->setPath('offline-chat.aspx');
		}

        }catch (\Exception $e){
            $this->messageManager->addExceptionMessage($e, __('We can\'t submit your request, Please try again.'));
            $objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            return $resultRedirect->setPath('offline-chat.aspx');
        }

    }

}
?>