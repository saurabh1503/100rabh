<?php


namespace TNA\Profile\Model\Customer\Attribute\Source;

class Owner extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => 'NO', 'label' => __('NO')],
                ['value' => 'YES', 'label' => __('YES')]
                
            ];
        }
        return $this->_options;
    }
}
