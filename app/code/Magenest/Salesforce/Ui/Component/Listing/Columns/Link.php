<?php
namespace Magenest\Salesforce\Ui\Component\Listing\Columns;

use Magenest\Salesforce\Model\Connector;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class Link
 * @package Magenest\Salesforce\Ui\Component\Listing\Columns
 */
class Link extends Column
{

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfigInterface;

    protected $instanceUrl;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components,
        array $data,
        ScopeConfigInterface $scopeConfigInterface
    ) {
    
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->scopeConfigInterface = $scopeConfigInterface;
        $this->instanceUrl = $this->scopeConfigInterface->getValue(Connector::XML_PATH_SALESFORCE_INSTANCE_URL);
    }

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
                if ($item['record_id']) {
                    $htmlLink = $this->renderLink($item['record_id']);
                    $item['record_id'] = '<a href="' . $htmlLink .'" target="_blank">View on Salesforce</a>';
                }
            }
        }
        return $dataSource;
    }

    /**
     * @param $recordId
     * @return string
     */
    private function renderLink($recordId)
    {
        $url = $this->instanceUrl.'/'. $recordId;
        return $url;
    }
}
