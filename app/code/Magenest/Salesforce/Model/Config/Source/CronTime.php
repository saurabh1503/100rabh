<?php
namespace Magenest\Salesforce\Model\Config\Source;

class CronTime implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Options array
     *
     * @var array
     */
    protected $_options = [
        0 => 'Sync manually',
        15 => '15 minutes',
        30 => '30 minutes',
        60 => '1 hour',
        120 => '2 hours'
    ];

    /**
     * Return options array
     * @return array
     */
    public function toOptionArray()
    {
        $options = $this->_options;
        return $options;
    }
}
