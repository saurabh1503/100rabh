<?php
/**
 * Copyright © 2015 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_Salesforce extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package  Magenest_Salesforce
 * @author   ThaoPV
 */
namespace Magenest\Salesforce\Controller\Adminhtml\Map;

use Magenest\Salesforce\Controller\Adminhtml\Map as MapController;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magenest\Salesforce\Model\MapFactory;
use Magenest\Salesforce\Model\ResourceModel\Map\CollectionFactory as MapCollectionFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;

class Edit extends MapController
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context              $context
     * @param Registry             $coreRegistry
     * @param MapFactory           $mapFactory
     * @param PageFactory          $resultPageFactory
     * @param ForwardFactory       $resultForwardFactory
     * @param MapCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        MapFactory $mapFactory,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        MapCollectionFactory $collectionFactory
    ) {
        $this->coreRegistry         = $coreRegistry;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context, $coreRegistry, $resultPageFactory, $mapFactory, $collectionFactory);
    }

    /**
     * Edit Mapping
     *
     * @return                                  \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id    = $this->getRequest()->getParam('id');
        $model = $this->_mapFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This mapping no longer exists.'));
                /*
                    * \Magento\Backend\Model\View\Result\Redirect $resultRedirect
                */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->coreRegistry->register('mapping', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? __('Edit Mapping %1', $model->getType()) : __('New Mapping'));

        return $resultPage;
    }
}
