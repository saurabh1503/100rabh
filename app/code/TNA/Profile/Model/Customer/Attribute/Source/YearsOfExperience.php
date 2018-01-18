<?php


namespace TNA\Profile\Model\Customer\Attribute\Source;

class YearsOfExperience extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => '3_6', 'label' => __('3 TO 6')],
                ['value' => '7_10', 'label' => __('7 TO 10 ')],
                ['value' => '11_20', 'label' => __('11 TO 20')],
                ['value' => '20_OR_MORE', 'label' => __('20 OR MORE')]
            ];
        }
        return $this->_options;
    }
}
