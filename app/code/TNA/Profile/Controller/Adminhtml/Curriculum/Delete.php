<?php


namespace TNA\Profile\Controller\Adminhtml\Curriculum;

class Delete extends \TNA\Profile\Controller\Adminhtml\Curriculum
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
        $id = $this->getRequest()->getParam('curriculum_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('TNA\Profile\Model\Curriculum');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the Curriculum.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['curriculum_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a curriculum to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
