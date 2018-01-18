<?php 

namespace Efloor\Requestquote\Controller\Index;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Controller\ResultFactory;

class Post extends \Magento\Framework\App\Action\Action {


public function __construct(
        \Magento\Framework\App\Action\Context $context,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig

    )
    {
        parent::__construct($context);
		$this->scopeConfig = $scopeConfig;
	
    }

/**
* @var Google reCaptcha Options
*/  
	private static $_siteVerifyUrl = $this->scopeConfig->getValue('accordian/parameters/api_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	private $_secret;
	private static $_version = "php_1.0";
/**
    /**
     * Post user question
     *
     * @return void
     * @throws \Exception
     */
    public function execute() {
		
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$secret_key= $this->scopeConfig->getValue('accordian/parameters/secret_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $captcha = $this->getRequest()->getPostValue('g-recaptcha-response');
		$secret = $secret_key; //Replace with your secret key
		$response = null;
		$path = self::$_siteVerifyUrl;
		$dataC = array (
		'secret' => $secret,
		'remoteip' => $_SERVER["REMOTE_ADDR"],
		'v' => self::$_version,
		'response' => $captcha
		);
		$req = "";
		foreach ($dataC as $key => $value) {
			 $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
		}

		$req = substr($req, 0, strlen($req)-1);
		$response = file_get_contents($path . $req);
		$answers = json_decode($response, true);
	//if(trim($answers ['success']) == true) {
		
        $post = $this->getRequest()->getPostValue();
        if (!$post) {
            $this->_redirect('');
            return;
        }
		


        try {
        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($post);

        $error = false;

        if (!\Zend_Validate::is(trim($post['fullname']), 'NotEmpty')) {
            $error = true;
        }

        if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
            $error = true;
        }

        if ($error) {
            throw new \Exception();
        }
             

        $cname = $post['fullname'];
        $cemail = $post['email'];
        $cmobile = $post['phone'];
        $address1 = $post['firstaddress'];
        $address2 = $post['secondaddress'];
        $city = $post['city'];
        $state = $post['state'];
        $zip = $post['zip'];
        $country = $post['country'];
        $memo = $post['memo'];
        $best_discribe = $post['best_discribe'];
        $contactby = $post['contactby'];
        $product_description_product1 = $post['product_description_product1'];
        $product_description_product2 = $post['product_description_product2'];
        $product_description_product3 = $post['product_description_product3'];
		if(!empty($post['product_description_product4'])){
        $product_description_product4 = $post['product_description_product4'];}
		if(!empty($post['product_description_product5'])){
        $product_description_product5 = $post['product_description_product5'];}
        $product_description_qty1 = $post['product_description_qty1'];
        $product_description_qty2 = $post['product_description_qty2'];
        $product_description_qty3 = $post['product_description_qty3'];
		if(!empty($post['product_description_qty4'])){
        $product_description_qty4 = $post['product_description_qty4'];}
		if(!empty($post['product_description_qty5'])){
        $product_description_qty5 = $post['product_description_qty5'];
		}


		$Format = $objectManager->create('Efloor\Requestquote\Model\Submitquote');
		$products = $objectManager->create('Efloor\Requestquote\Model\Quoteproduct');

		$Format->setData($post);
		$products->setData($post);

		$Format->save();
		$products->save();
		
		$transportBuilder = $objectManager->create('\Magento\Framework\Mail\Template\TransportBuilder');
		$transport = $transportBuilder
                ->setTemplateIdentifier(22)
                ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(['data' => $postObject])
                ->setFrom(['name' => $cname,'email' => $cemail])
                ->addTo('info@efloors.com')
                ->getTransport();
            $transport->sendMessage();
		
            $this->messageManager->addSuccess(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
            $this->_redirect('*/*/'); // change here 
            return;
        } catch (\Exception $e) {
           
            $this->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.')
            );
            $this->_redirect('*/*/');  // change here 
            return;
        }
	
	

  

}
}
