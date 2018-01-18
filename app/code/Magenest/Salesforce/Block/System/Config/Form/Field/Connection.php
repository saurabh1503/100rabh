<?php
namespace Magenest\Salesforce\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field as ConfigFormField;
use Magenest\Salesforce\Block\Adminhtml\Connection\Status as ConnectionStatus;

class Connection extends ConfigFormField
{
    /**
     * Create element for Access token field in store configuration
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $connectionHtml = $this->getLayout()->createBlock(ConnectionStatus::class)->toHtml();

        return $element->getElementHtml() . $connectionHtml;
    }
}
