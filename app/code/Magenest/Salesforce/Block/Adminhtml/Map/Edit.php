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
namespace Magenest\Salesforce\Block\Adminhtml\Map;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

/**
 * Class Edit
 *
 * @package Magenest\Salesforce\Block\Adminhtml\Map
 */
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context  $context
     * @param Registry $registry
     * @param array    $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize blog post edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'Magenest_Salesforce';
        $this->_controller = 'adminhtml_map';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Mapping'));
        $this->buttonList->add(
            'saveandcontinue',
            [
             'label'          => __('Save and Continue Edit'),
             'class'          => 'save',
             'data_attribute' => [
                                  'mage-init' => [
                                                  'button' => [
                                                               'event'  => 'saveAndContinueEdit',
                                                               'target' => '#edit_form',
                                                              ],
                                                 ],
                                 ],
            ],
            -100
        );
        $this->buttonList->add(
            'updateallfields',
            [
             'label' => __('Refresh Fields'),
            ],
            -90
        );

        $this->buttonList->update('delete', 'label', __('Delete'));
    }

    /**
     * Retrieve text for header element depending on loaded post
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('mapping')->getId()) {
            return __("Edit Mapping '%1'", $this->escapeHtml($this->_coreRegistry->registry('mapping')->getType()));
        } else {
            return __('New Mapping');
        }
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('salesforce/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }
}
