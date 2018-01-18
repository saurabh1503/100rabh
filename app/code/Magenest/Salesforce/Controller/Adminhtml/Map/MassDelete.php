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

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magenest\Salesforce\Controller\Adminhtml\Map as MapController;
use Magento\Framework\View\Result\PageFactory;
use Magenest\Salesforce\Model\MapFactory;
use Magenest\Salesforce\Model\ResourceModel\Map\CollectionFactory as MapCollectionFactory;

/**
 * Class MassDelete Map
 *
 * @package Magenest\Salesforce\Controller\Adminhtml\Map
 */
class MassDelete extends MapController
{
    /**
     * Mass Action Filter
     *
     * @var Filter
     */
    protected $_filter;

    /**
     * Collection Factory
     *
     * @var MapCollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param Context              $context
     * @param Registry             $coreRegistry
     * @param PageFactory          $resultPageFactory
     * @param MapFactory           $mapFactory
     * @param MapCollectionFactory $collectionFactory
     * @param Filter               $filter
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        MapFactory $mapFactory,
        MapCollectionFactory $collectionFactory,
        Filter $filter
    ) {
        $this->_filter            = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $coreRegistry, $resultPageFactory, $mapFactory, $collectionFactory);
    }

    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());

        $delete = 0;
        foreach ($collection as $item) {
            /** @var \Magenest\Salesforce\Model\Map $item */
            $item->delete();
            $delete++;
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $delete));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
