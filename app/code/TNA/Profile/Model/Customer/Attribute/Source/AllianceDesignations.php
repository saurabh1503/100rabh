<?php


namespace TNA\Profile\Model\Customer\Attribute\Source;

class AllianceDesignations extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => (string) 'CIC', 'label' => __('CIC')],
                ['value' => (string) 'CRM', 'label' => __('CRM')],
                ['value' => (string) 'CISR', 'label' => __('CISR')],
				['value' => (string) 'CSRM', 'label' => __('CSRM')],
                ['value' => (string) '', 'label' => __('')]
            ];
        }
        return $this->_options;
    }
}
