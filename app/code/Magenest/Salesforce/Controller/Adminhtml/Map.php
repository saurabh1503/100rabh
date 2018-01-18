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
namespace Magenest\Salesforce\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magenest\Salesforce\Model\MapFactory;
use Magenest\Salesforce\Model\ResourceModel\Map\CollectionFactory as MapCollectionFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * Reviews admin controller
 */
abstract class Map extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Map model factory
     *
     * @var \Magenest\Salesforce\Model\MapFactory
     */
    protected $_mapFactory;

    /**
     * Map Collection factory
     *
     * @var \Magenest\Salesforce\Model\MapFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Page factory
     *
     * @var PageFactory
     */
    protected $_resultPage;

    /**
     * @param Context              $context
     * @param Registry             $coreRegistry
     * @param PageFactory          $resultPageFactory
     * @param MapFactory           $mapFactory
     * @param MapCollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        MapFactory  $mapFactory,
        MapCollectionFactory $collectionFactory
    ) {
        $this->_context           = $context;
        $this->coreRegistry       = $coreRegistry;
        $this->_mapFactory        = $mapFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->resultPageFactory  = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magenest_Salesforce::mapping')
            ->addBreadcrumb(__('Manage Mapping'), __('Manage Mapping'));

        return $resultPage;
    }

    /**
     * Check ACL
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Salesforce::mapping');
    }
}
