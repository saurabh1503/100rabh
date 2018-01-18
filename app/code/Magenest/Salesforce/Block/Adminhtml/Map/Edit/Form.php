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
namespace Magenest\Salesforce\Block\Adminhtml\Map\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;

/**
 * Adminhtml blog post edit form block
 *
 * @author Magento Core Team <core@magentocommerce.com>
 */
class Form extends Generic
{
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
             'data' => [
                        'id'     => 'edit_form',
                        'action' => $this->getData('action'),
                        'method' => 'post',
                       ],
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
