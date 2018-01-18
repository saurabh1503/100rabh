<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Fcamodule\Contact\Block;
/**
 * Product price block
 */
class Callforprice extends \Magento\Catalog\Pricing\Render\FinalPriceBox 
{
  
     /**
     * Wrap with standard required container
     *
     * @param string $html
     * @return string
     */
    public function wrapResult($html)
    {
		/** @var \Magento\Framework\App\ObjectManager $ */
$obm = \Magento\Framework\App\ObjectManager::getInstance();
/** @var \Magento\Framework\App\Http\Context $context */
$context = $obm->get('Magento\Framework\App\Http\Context');
/** @var bool $isLoggedIn */
$isLoggedIn = $context->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
		
		if($isLoggedIn):
          return '<div class="price-box ' . $this->getData('css_classes') . '" ' .
            'data-role="priceBox" ' .
            'data-product-id="' . $this->getSaleableItem()->getId() . '"' .
            '>' . $html . '</div>';
       else :
       	return '<div class="price-box "><span></span></div>';
       endif;
    }
    
}