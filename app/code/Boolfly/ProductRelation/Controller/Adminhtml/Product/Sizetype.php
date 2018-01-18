<?php

namespace Boolfly\ProductRelation\Controller\Adminhtml\Product;

class Sizetype extends \Magento\Catalog\Controller\Adminhtml\Product
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    )
    {
        parent::__construct($context, $productBuilder);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Get products grid and serializer block
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $this->productBuilder->build($this->getRequest());
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('catalog.product.edit.tab.sizetype')
            ->setProductsSizetype($this->getRequest()->getPost('products_sizetype', null));
        return $resultLayout;
    }
}