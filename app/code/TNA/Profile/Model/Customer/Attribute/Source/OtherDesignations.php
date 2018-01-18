<?php


namespace TNA\Profile\Model\Customer\Attribute\Source;

class OtherDesignations extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => (string) 'c', 'label' => __('c')],
                ['value' => (string) 'a', 'label' => __('a')],
                ['value' => (string) 'd', 'label' => __('d')]
            ];
        }
        return $this->_options;
    }
}
