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

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Magenest\Salesforce\Model\Connector;

class Refresh extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magenest\Salesforce\Model\Connector
     */
    protected $_connector;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     * @param Connector   $connector
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Connector $connector
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_connector        = $connector;
    }

    /**
     * execute
     */
    public function execute()
    {
        $reponse = $this->_connector->getAccessToken();

        if (!empty($reponse['access_token'])) {
            $this->messageManager->addSuccess('Refesh access token of SalesforceCRM success !');
            $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl($this->getUrl('*')));
            return;
        } else {
            $this->messageManager->addError('Can\'t refesh access token, please check in configuration!');
        }

        $this->_redirect('adminhtml/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
}
