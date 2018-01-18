<?php
namespace Magenest\Salesforce\Model\Report;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 * @package Magenest\Salesforce\Model\Log
 */
class Status implements ArrayInterface
{

    /**@#+
     * constant
     */
    const ERROR_STATUS = 2;
    const SUCCESS_STATUS = 1;

    /**
     * Options array
     *
     * @var array
     */
    protected $_options = [
        self::ERROR_STATUS => 'Error',
        self::SUCCESS_STATUS => 'Success'
    ];

    /**
     * Return options array
     * @return array
     */
    public function toOptionArray()
    {
        $res = [];
        foreach ($this->toArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->_options;
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::ERROR_STATUS => 'Success',
            self::SUCCESS_STATUS => 'Error'
        ];
    }
}
