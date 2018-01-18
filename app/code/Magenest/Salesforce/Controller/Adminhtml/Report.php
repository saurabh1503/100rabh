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

use Magenest\Salesforce\Model\ReportFactory as ReportFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\Model\View\Result\Page;

/**
 * Class Report
 * @package Magenest\Salesforce\Controller\Adminhtml
 */
abstract class Report extends Action
{
    /**
     * @var ReportFactory
     */
    protected $_reportFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Page factory
     *
     * @var Page
     */
    protected $_resultPage;

    /**
     * @param Context        $context
     * @param ReportFactory  $reportFactory
     * @param LayoutFactory  $layoutFactory
     * @param PageFactory    $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     */
    public function __construct(
        Context $context,
        ReportFactory $reportFactory,
        LayoutFactory $layoutFactory,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory
    ) {
        $this->_reportFactory       = $reportFactory;
        $this->layoutFactory        = $layoutFactory;
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * instantiate result page object
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
     * set page data
     *
     * @return $this
     */
    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->getConfig()->getTitle()->prepend((__('View Report')));
        return $this;
    }

    /**
     * Check ACL
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenest_Salesforce::report');
    }
}
