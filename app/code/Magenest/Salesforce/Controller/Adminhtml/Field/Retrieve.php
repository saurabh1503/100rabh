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
namespace Magenest\Salesforce\Controller\Adminhtml\Field;

use Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use Magenest\Salesforce\Model\FieldFactory;

class Retrieve extends Action
{
    /**
     * @var \Magenest\Salesforce\Model\FieldFactory
     */
    protected $_fieldFactory;

    /**
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magenest\Salesforce\Model\FieldFactory $fieldFactory
     */
    public function __construct(
        Context $context,
        FieldFactory $fieldFactory
    ) {
        parent::__construct($context);
        $this->_fieldFactory = $fieldFactory;
    }

    /**
     * Execute
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $type = $data['type'];
            $out  = [];
            $out['salesforce_options'] = '';
            $out['magento_options']    = '';
            if ($type) {
                $model = $this->_fieldFactory->create();
                $model->loadByTable($type);
                $magentoFields = $model->getMagentoFields();

                $magentoOption = '';

                if ($magentoFields) {
                    foreach ($magentoFields as $value => $label) {
                        $magentoOption .= "<option value ='$value' >".$label."</option>";
                    }
                }

                $out['magento_options'] = $magentoOption;
                $salesforceFields       = $model->getSalesforceFields();
                $salesforceOption       = '';

                if ($salesforceFields) {
                    foreach ($salesforceFields as $value => $label) {
                        $salesforceOption .= "<option value ='$value' >".$label."</option>";
                    }
                }

                $out['salesforce_options'] = $salesforceOption;
            }

            echo json_encode($out);
        }
    }
}
