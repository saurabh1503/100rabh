<?php


namespace TNA\Events\Controller\Adminhtml\Continuingeducation;

class Delete extends \TNA\Events\Controller\Adminhtml\Continuingeducation
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
        $id = $this->getRequest()->getParam('continuing_education_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('TNA\Events\Model\ContinuingEducation');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the Continuing Education.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['continuing_education_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a Continuing Education to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
