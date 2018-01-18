<?php


namespace TNA\Events\Controller\Adminhtml\Continuingeducation;

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
            $id = $this->getRequest()->getParam('continuing_education_id');
        
            $model = $this->_objectManager->create('TNA\Events\Model\ContinuingEducation')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This Continuing Education no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        
            $model->setData($data);
        
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the Continuing Education.'));
                $this->dataPersistor->clear('tna_events_continuing_education');
        
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['continuing_education_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Continuing Education.'));
            }
        
            $this->dataPersistor->set('tna_events_continuing_education', $data);
            return $resultRedirect->setPath('*/*/edit', ['continuing_education_id' => $this->getRequest()->getParam('continuing_education_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
