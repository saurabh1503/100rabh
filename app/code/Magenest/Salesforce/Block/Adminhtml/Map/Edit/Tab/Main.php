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
namespace Magenest\Salesforce\Block\Adminhtml\Map\Edit\Tab;

use \Magento\Backend\Block\Widget\Form\Generic;
use \Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;
use Magenest\Salesforce\Model\Status;
use Magenest\Salesforce\Model\FieldFactory;

/**
 * Mapping form main tab
 */
class Main extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magenest\Salesforce\Model\Status
     */
    protected $_status;

    /**
     * @var \Magenest\Salesforce\Model\FieldFactory
     */
    protected $_fieldFactory;


    /**
     * @param Context      $context
     * @param Registry     $registry
     * @param FormFactory  $formFactory
     * @param Store        $systemStore
     * @param Status       $status
     * @param FieldFactory $fieldFactory
     * @param array        $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Status $status,
        FieldFactory $fieldFactory,
        array $data = []
    ) {
        $this->_systemStore  = $systemStore;
        $this->_status       = $status;
        $this->_fieldFactory = $fieldFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return                                        $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var $model \Magenest\Salesforce\Model\Map */
        $model = $this->_coreRegistry->registry('mapping');

        $isElementDisabled = false;
        $_model            = $this->_fieldFactory->create();

        $_type = $_model->changeFields();

        /*
            * @var \Magento\Framework\Data\Form $form
        */
        $form = $this->_formFactory->create();
        $salesforceFields = [];
        $magentoFields    = [];
        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Mapping Information')]);

        if ($model->getId()) {
            $type = $model->getType();
            $_model->setType($type);
            $salesforceFields = $_model->getSalesforceFields();
            $magentoFields    = $_model->getMagentoFields();
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
            $isElementDisabled = true;
        }

        $fieldset->addField(
            'type',
            'select',
            [
             'name'     => 'type',
             'label'    => __('Type'),
             'title'    => __('Type'),
             'required' => true,
             'options'  => $_type,
             'disabled' => $isElementDisabled,
            ]
        );
        $fieldset->addField(
            'magento',
            'select',
            [
             'name'     => 'magento',
             'label'    => __('Magento Field'),
             'title'    => __('Magento Field'),
             'required' => true,
             'values'   => $magentoFields,
            ]
        );

        $fieldset->addField(
            'salesforce',
            'select',
            [
             'name'     => 'salesforce',
             'label'    => __('Salesforce Field'),
             'title'    => __('Salesforce Field'),
             'required' => true,
             'values'   => $salesforceFields,
            ]
        );
        $fieldset->addField(
            'description',
            'textarea',
            [
             'name'     => 'description',
             'label'    => __('Description'),
             'title'    => __('Description'),
             'required' => true,
            ]
        );

        $fieldset->addField(
            'status',
            'select',
            [
             'label'    => __('Status'),
             'title'    => __('Status'),
             'name'     => 'status',
             'required' => true,
             'options'  => $this->_status->getOptionArray(),
            ]
        );
        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Mapping Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Mapping Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param  string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
