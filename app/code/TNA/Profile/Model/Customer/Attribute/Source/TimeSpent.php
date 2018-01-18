<?php


namespace TNA\Profile\Model\Customer\Attribute\Source;

class TimeSpent extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => 'COMMERCIAL_LINES', 'label' => __('COMMERCIAL LINES')],
                ['value' => 'PERSONAL_LINES', 'label' => __('PERSONAL LINES')],
                ['value' => 'EMPLOYEE_BENEFITS_LIFE_HEALTH', 'label' => __('EMPLOYEE BENEFITS LIFE HEALTH')],
				['value' => 'OTHER', 'label' => __('OTHER')]
            ];
        }
        return $this->_options;
    }
}
