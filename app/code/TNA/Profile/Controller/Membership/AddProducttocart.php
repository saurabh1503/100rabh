<?php
use Magento\Framework\App\RequestInterface;
namespace TNA\Profile\Controller\Membership;
class AddProducttocart extends \Magento\Customer\Controller\AbstractAccount
{

    protected $cart;
         /**
          * @var \Magento\Catalog\Model\Product
          */
         protected $product;

         public function __construct(
             \Magento\Framework\App\Action\Context $context,
             \Magento\Framework\View\Result\PageFactory $resultPageFactory,
             \Magento\Checkout\Model\Cart $cart,
			 \Magento\Framework\App\RequestInterface $request,
			 \Magento\Catalog\Model\ProductFactory $productFactory,
			 \Magento\Catalog\Model\ProductRepository $productRepository,
			 \Magento\Store\Model\StoreManagerInterface $storeManager
         ) {
             $this->resultPageFactory = $resultPageFactory;
             $this->cart = $cart;
             $this->_request = $request;
			 $this->productFactory = $productFactory;
			 $this->_productRepository = $productRepository;
			 $this->_storeManager = $storeManager;
             parent::__construct($context);
         }
         public function execute()
         {
             try {
				  
				  $data = $data = $this->_request->getParams();
				  //echo '<pre>';print_r($data);die;
				  /*$productInfo = $this->productFactory->create()->loadByAttribute('sku', 'membership_academy');
				  $skus = array();
			  
				  if(isset($data['sku_due_cic'])){
					array_push($skus, $data['sku_due_cic']);
				  }
				  if(isset($data['sku_due_cisr'])){
					array_push($skus, $data['sku_due_cisr']);
				  }
				  if(isset($data['sku_due_crm'])){
					array_push($skus, $data['sku_due_crm']);
				  }
				  if(isset($data['sku_due_csrm'])){
					array_push($skus, $data['sku_due_csrm']);
				  }
				  if(isset($data['sku_due_cprm'])){
					array_push($skus, $data['sku_due_cprm']);
				  }
				  */
				  $params = array();
				  $product_ids = array();
				   /*if(isset($data['additional_product'])){
				    $productInfo = $this->productFactory->create()->loadByAttribute('sku', 'membership_academy');
					$productId = $productInfo->getEntityId();
					array_push($data['product_ids'], $productId);
					}
				  
				  */
				 if(isset($data['product_ids'])){ 
			     foreach($data['product_ids'] as $productData){
				 
					$productId = $productData;
					$product_ids[] = $productData;
				    $params = array(
					'product' => $productId,
					);
					 $params['qty'] = 1;
					 $_product = $this->_productRepository->getById($productId);
                     //$this->cart->addProduct($_product, $params);
                     //$this->cart->save();
                   }
                }
				if(count($product_ids) > 0){
					 $this->cart->addProductsByIds($product_ids);
					 $this->cart->save();
					
				}
				
				
				
                 $this->messageManager->addSuccess(__('Add to cart successfully.'));
             } catch (\Magento\Framework\Exception\LocalizedException $e) {
                 $this->messageManager->addException(
                     $e,
                     __('%1', $e->getMessage())
                 );
             } catch (\Exception $e) {
                 $this->messageManager->addException($e, __('We can not add products in cart.'));
             }
             /*cart page*/
			 $redirectUrl = $this->_storeManager->getStore()->getUrl('checkout/cart/index');
             $this->getResponse()->setRedirect($redirectUrl);


         }
    }
