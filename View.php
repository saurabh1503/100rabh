<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Efloor\Common\Block\Category;

/**
 * Class View
 * @package Magento\Catalog\Block\Category
 */
class View extends \Magento\Catalog\Block\Category\View
{
   
   
    /**
     * @return mixed
     */
    public function getCmsBlockHtml()
    {	
        if (!$this->getData('cms_block_html')) {
            $html = $this->getLayout()->createBlock(
                'Magento\Cms\Block\Block'
            )->setBlockId(
                $this->getCurrentCategory()->getLandingPage()
            )->toHtml();
            $this->setData('cms_block_html', $html);
        }
        return $this->getData('cms_block_html');
    }

   
}
