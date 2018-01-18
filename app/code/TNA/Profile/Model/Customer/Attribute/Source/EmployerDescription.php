<?php


namespace TNA\Profile\Model\Customer\Attribute\Source;

class EmployerDescription extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => 'SCHOOL', 'label' => __('School or School District')],
                ['value' => 'ASSOCIATION', 'label' => __('Association')],
                ['value' => 'FINANCIAL_INSTITUTION', 'label' => __('Financial Institution')],
                ['value' => 'GOVERNMENT_AGENCY', 'label' => __('Government Agency')],
                ['value' => 'INSURANCE_AGENT', 'label' => __('Insurance Agent')],
                ['value' => 'INSURANCE_CARRIER', 'label' => __('Insurance Carrier')],
                ['value' => 'NON_INSURANCE', 'label' => __('Non-Insurance Organization')],
                ['value' => 'UNEMPLOYED', 'label' => __('Presently Unemployed')],
                ['value' => 'UNIVERSITY', 'label' => __('University or College')],
                ['value' => 'OTHER', 'label' => __('Other')]
            ];
        }
        return $this->_options;
    }
}
