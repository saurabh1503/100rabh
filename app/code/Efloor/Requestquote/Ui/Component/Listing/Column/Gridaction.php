<?php
namespace Efloor\Requestquote\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class GridAction extends Column
{
    /** Url path */
   const REVIEW_URL_PATH_EDIT = 'requestquote/grid/edit';
   const REVIEW_URL_PATH_DELETE = 'requestquote/grid/delete';
   const REVIEW_URL_PATH_VIEW = 'requestquote/view/index';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * Class constructor
     * 
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
    
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $editUrl = self::REVIEW_URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
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
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['id'])) {
                    
                    $item[$name]['view'] = [
                        'href' => $this->urlBuilder->getUrl(self::REVIEW_URL_PATH_VIEW, ['id' => $item['id']]),
                        'label' => __('View')
                    ];
                    
                    
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $item['id']]),
                        'label' => __('Edit')
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::REVIEW_URL_PATH_DELETE, ['id' => $item['id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            //'title' => __('Delete "${ $.$data.fullname }"'),
                            'message' => __('Are you sure you wan\'t to delete this record?')
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
