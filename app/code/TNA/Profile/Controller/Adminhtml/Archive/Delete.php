<?php


namespace TNA\Profile\Controller\Adminhtml\Archive;

class Delete extends \TNA\Profile\Controller\Adminhtml\Archive
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('archive_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('TNA\Profile\Model\Archive');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the Archive.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['archive_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a Archive to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
