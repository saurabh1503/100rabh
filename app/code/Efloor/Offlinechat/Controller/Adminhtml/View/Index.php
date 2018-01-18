<?php
namespace Efloor\Offlinechat\Controller\Adminhtml\View;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    
    public $post;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */


    /**
     * Is the user allowed to view the review post grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Efloor_Offlinechat::grid');
    }

    
       /**
     * Review Index, shows a list of recent review posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
         $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Efloor\Offlinechat\Model\Grid');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Offlinechat is no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        
    }
    
    public function getCompleteData() {
    
    return $this->getSelect()->joinInner(
            ['secondTable' => $this->getTable('efloor_requestquote_product')], //2nd table name by which you want to join mail table
            'efloor_requestquote_detail.id = secondTable.requestquote_detail_id WHERE ', // common column which available in both table 
            ['product_description'] // '*' define that you want all column of 2nd table. if you want some particular column then you can define as ['column1','column2']
        );
    
    }

}