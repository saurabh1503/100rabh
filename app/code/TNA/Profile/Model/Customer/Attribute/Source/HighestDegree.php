<?php


namespace TNA\Profile\Model\Customer\Attribute\Source;

class HighestDegree extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
                ['value' => 'HIGH_SCHOOL', 'label' => __('HIGH SCHOOL')],
                ['value' => 'ASSOCIATE', 'label' => __('ASSOCIATE')],
                ['value' => 'BACHELOR’S DEGREE', 'label' => __('BACHELOR’S DEGREE')],
                ['value' => 'GRADUATE', 'label' => __('GRADUATE')],
                ['value' => 'DOCTORATE', 'label' => __('DOCTORATE')],
                ['value' => 'NONE', 'label' => __('NONE')]
            ];
        }
        return $this->_options;
    }
}
