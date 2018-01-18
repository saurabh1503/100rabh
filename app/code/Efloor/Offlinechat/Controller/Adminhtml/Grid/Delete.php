<?php
namespace Efloor\Offlinechat\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Delete extends \Magento\Backend\App\Action
{

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Efloor_Offlinechat::delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('Efloor\Offlinechat\Model\Grid');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The Offlinechat has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a offlinechat to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}