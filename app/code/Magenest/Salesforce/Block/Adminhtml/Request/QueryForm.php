<?php
namespace Magenest\Salesforce\Block\Adminhtml\Request;

/**
 * Class QueryForm
 * @package Magenest\Salesforce\Block\Adminhtml\Request
 */
class QueryForm extends \Magento\Backend\Block\Widget
{
    /**
     * QueryForm constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
}
