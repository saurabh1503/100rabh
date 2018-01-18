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

use Magenest\Salesforce\Model\FieldFactory;
use Magento\Backend\App\Action;

/**
 * Class UpdateAllFields
 *
 * @package Magenest\Salesforce\Controller\Adminhtml\Field
 */
class UpdateAllFields extends Action
{
    /**
     * @var \Magenest\Salesforce\Model\FieldFactory
     */
    protected $_fieldFactory;


    /**
     * @param FieldFactory $fieldFactory
     */
    public function __construct(
        FieldFactory $fieldFactory
    ) {
        $this->_fieldFactory = $fieldFactory;
    }

    /**
     * Execute
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $model = $this->_fieldFactory->create();
            $table = $model->getAllTable();
            foreach ($table as $s_table => $m_table) {
                $model = $this->_fieldFactory->create();
                $model->loadByTable($s_table, true);
            }
        }

        return;
    }
}
