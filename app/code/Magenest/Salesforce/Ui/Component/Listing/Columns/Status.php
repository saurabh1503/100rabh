<?php
namespace Magenest\Salesforce\Ui\Component\Listing\Columns;

use Magento\Ui\Component\Listing\Columns\Column;
use Magenest\Salesforce\Model\Report\Status as LogStatus;

/**
 * Class Link
 * @package Magenest\Salesforce\Ui\Component\Listing\Columns
 */
class Status extends Column
{

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item['status'] && $item['status'] == LogStatus::SUCCESS_STATUS) {
                    $class = 'notice';
                    $label = 'Success';
                } else {
                    $class = 'critical';
                    $label = 'Error';
                }
                $item['status'] = '<span class="grid-severity-'
                    . $class .'">'. $label .'</span>';
            }
        }
        return $dataSource;
    }
}
