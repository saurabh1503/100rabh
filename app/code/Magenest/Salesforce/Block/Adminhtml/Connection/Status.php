<?php
namespace Magenest\Salesforce\Block\Adminhtml\Connection;

use Magento\Backend\Block\Template;

class Status extends Template
{

    /**
     * Set Template
     *
     * @var string
     */
    protected $_template = 'system/config/connection/status.phtml';

    public function isConnected()
    {
        return $this->_scopeConfig->isSetFlag('salesforcecrm/config/is_connected');
    }
}
