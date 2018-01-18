<?php

namespace Efloor\Requestquote\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
// use Magento\TestFramework\ErrorLog\Logger;   //This class may be use in future for generate the log

class Save extends \Magento\Backend\App\Action {

    /**
     * Class constructor
     * 
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context) {
        parent::__construct($context);
    }

    /**
     * Check permission
     * 
     * @return boolean
     */
    protected function _isAllowed() {
        return $this->_authorization->isAllowed('Efloor_Requestquote::save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute() {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        

        if ($data) {
            /** @var \Efloor\Review\Model\Post $model */
            $model = $this->_objectManager->create('Efloor\Requestquote\Model\Grid');

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);



        
                $model->save();
                $this->messageManager->addSuccess(__('You saved this Requestquote.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');


            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

}