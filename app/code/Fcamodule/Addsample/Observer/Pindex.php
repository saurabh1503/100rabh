<?php
    namespace Fcamodule\Addsample\Observer;
    use Magento\Framework\Event\ObserverInterface;
    use Magento\Framework\Event\Observer;

    class Pindex implements ObserverInterface {

        public function __construct(
            
            \Magento\Catalog\Block\Product\Context $context,         
            \Magento\Catalog\Model\ProductRepository $productRepository
        ){
            $this->_request = $context->getRequest();
            $this->_productRepository = $productRepository;
        }

        public function execute(\Magento\Framework\Event\Observer $observer ) {
            $productId = $this->_request->getParam('id');
            $product   = $this->_productRepository->getById($productId);
			$pindex    = $product->getPpopularityindex();
			$product->setPpopularityindex($pindex+1);
            $product->save();
           
		   }
        }
    