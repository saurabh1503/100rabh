<?php


namespace TNA\Profile\Model\Customer\Attribute\Source;

class ProfessionalFocus extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    protected $_optionsData;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct()
    {
    }

    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['value' => '0', 'label' => __('')],
                ['value' => 'CLAIMS', 'label' => __('Claims')],
                ['value' => 'CUSTOMER_SERVICE', 'label' => __('CUSTOMER SERVICE')],
                ['value' => 'FINANCE_ACCOUNTING', 'label' => __('FINANCE ACCOUNTING')],
                ['value' => 'HUMAN_RESOURCES', 'label' => __('HUMAN RESOURCES')],
                ['value' => 'LEGAL', 'label' => __('LEGAL')],
                ['value' => 'MARKETING', 'label' => __('MARKETING')],
                ['value' => 'OPERATIONS', 'label' => __('OPERATIONS')],
                ['value' => 'OTHER', 'label' => __('OTHER')],
                ['value' => 'RISK_MANAGEMENT', 'label' => __('RISK MANAGEMENT')],
                ['value' => 'SAFETY_CONTROL', 'label' => __('SAFETY AND LOSS CONTROL')],
                ['value' => 'SALES', 'label' => __('SALES')],
                ['value' => 'TRAINING', 'label' => __('TRAINING AND EDUCATION')],
                ['value' => 'UNDERWRITING', 'label' => __('UNDERWRITING')]
            ];
        }
        return $this->_options;
    }
}
