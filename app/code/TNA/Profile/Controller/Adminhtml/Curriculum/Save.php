<?php


namespace TNA\Profile\Controller\Adminhtml\Curriculum;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('curriculum_id');
        
            $model = $this->_objectManager->create('TNA\Profile\Model\Curriculum')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This curriculum no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the curriculum.'));
                $this->dataPersistor->clear('tna_profile_curriculum');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['curriculum_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the curriculum.'));
            }
        
            $this->dataPersistor->set('tna_profile_curriculum', $data);
            return $resultRedirect->setPath('*/*/edit', ['curriculum_id' => $this->getRequest()->getParam('curriculum_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
