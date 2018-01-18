<?php
/**
 * * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\Salesforce\Controller\Adminhtml\System\Config\Getauth;

use Magenest\Salesforce\Model\Connector;
use Magento\Backend\App\Action;
use Magento\Config\Model\Config as ConfigModel;

/**
 * Class GetAuth
 * @package Magenest\Salesforce\Controller\Adminhtml\System\Config\Getauth
 */
class Disconnect extends Action
{
    protected $_configModel;

    /**
     * Disconnect constructor.
     * @param Action\Context $context
     * @param ConfigModel $configModel
     */
    public function __construct(
        Action\Context $context,
        ConfigModel $configModel
    ) {
        parent::__construct($context);
        $this->_configModel = $configModel;
    }

    public function execute()
    {
        $this->_configModel->setDataByPath(Connector::XML_PATH_SALESFORCE_IS_CONNECTED, 0);
        $this->_configModel->save();
        $this->_configModel->setDataByPath(Connector::XML_PATH_SALESFORCE_ACCESS_TOKEN, null);
        $this->_configModel->save();
        $this->_configModel->setDataByPath(Connector::XML_PATH_SALESFORCE_INSTANCE_URL, null);
        $this->_configModel->save();
    }
}
