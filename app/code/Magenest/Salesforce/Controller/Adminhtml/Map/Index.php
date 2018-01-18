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
use Magenest\Salesforce\Model\MapFactory;
use Magento\Framework\View\Result\PageFactory;
use Magenest\Salesforce\Model\ResourceModel\Map\CollectionFactory as MapCollectionFactory;
use Magenest\Salesforce\Controller\Adminhtml\Map as MapController;

/**
 * Class Index Controller
 *
 * @package Magenest\Salesforce\Controller\Adminhtml\Map
 */
class Index extends MapController
{
    /**
     * execute the action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_setPageData();
        return $this->getResultPage();
    }

    /**
     * Instantiate result page object
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page
     */
    public function getResultPage()
    {
        if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->resultPageFactory->create();
        }

        return $this->_resultPage;
    }

    /**
     * Set page data
     *
     * @return $this
     */
    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->getConfig()->getTitle()->prepend((__('Manage Mapping')));
        return $this;
    }
}
