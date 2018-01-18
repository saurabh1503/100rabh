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
namespace Magenest\Salesforce\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class GetAuth
 *
 * @package Magenest\Salesforce\Block\Adminhtml\System\Config
 */
class Disconnect extends Field
{
    /**
     * Get Auth Token Label
     *
     * @var string
     */
    protected $_disconnectButtonLabel = 'Disconnect';

    /**
     * @param $disconnectButtonLabel
     * @return $this
     */
    public function setButtonLabel($disconnectButtonLabel)
    {
        $this->_disconnectButtonLabel = $disconnectButtonLabel;
        return $this;
    }

    /**
     * Set template to itself
     *
     * @return \Magenest\Salesforce\Block\Adminhtml\System\Config\Disconnect
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/connection/disconnect.phtml');
        }

        return $this;
    }

    /**
     * Unset some non-related element parameters
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * Get the button and scripts contents
     *
     * @param  \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $originalData = $element->getOriginalData();
        $buttonLabel  = !empty($originalData['button_label']) ? $originalData['button_label'] : $this->_authButtonLabel;
        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'html_id'      => $element->getHtmlId(),
                'ajax_url'     => $this->_urlBuilder->getUrl('salesforce/system_config_getauth/getAuth'),
            ]
        );

        return $this->_toHtml();
    }
}
