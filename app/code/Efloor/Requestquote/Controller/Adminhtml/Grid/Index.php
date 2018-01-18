<?php
namespace Efloor\Requestquote\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Efloor_Rquestquote::Grid');
        $resultPage->addBreadcrumb(__('Request Quote'), __('Request Quote'));
        $resultPage->addBreadcrumb(__('Manage Request Quote'), __('Manage Request Quote'));
        $resultPage->getConfig()->getTitle()->prepend(__('Request Quote'));

        return $resultPage;
    }

    /**
     * Is the user allowed to view the review post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Efloor_Requestquote::grid');
    }


}