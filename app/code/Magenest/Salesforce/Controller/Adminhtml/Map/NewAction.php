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
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magenest\Salesforce\Model\MapFactory;
use Magenest\Salesforce\Model\ResourceModel\Map\CollectionFactory as MapCollectionFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;

/**
 * Class NewAction: Create new a mapping
 *
 * @package Magenest\Salesforce\Controller\Adminhtml\Map
 */
class NewAction extends MapController
{
    /**
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    protected $resultForwardFactory;


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
        MapFactory  $mapFactory,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        MapCollectionFactory $collectionFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context, $coreRegistry, $resultPageFactory, $mapFactory, $collectionFactory);
    }

    /**
     * Forward to edit controller
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
