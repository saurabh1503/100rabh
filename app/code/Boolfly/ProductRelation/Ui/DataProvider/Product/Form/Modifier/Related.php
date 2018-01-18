<?php

namespace Boolfly\ProductRelation\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form\Fieldset;

class Related extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related
{
    const DATA_SCOPE_CUSTOMTYPE = 'customtype';
    const DATA_SCOPE_SIZETYPE = 'sizetype';

    /**
     * @var string
     */
    private static $previousGroup = 'search-engine-optimization';

    /**
     * @var int
     */
    private static $sortOrder = 90;

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                static::GROUP_RELATED => [
                    'children' => [
                        $this->scopePrefix . static::DATA_SCOPE_RELATED => $this->getRelatedFieldset(),
                        $this->scopePrefix . static::DATA_SCOPE_UPSELL => $this->getUpSellFieldset(),
                        $this->scopePrefix . static::DATA_SCOPE_CROSSSELL => $this->getCrossSellFieldset(),
                        $this->scopePrefix . static::DATA_SCOPE_CUSTOMTYPE => $this->getCustomTypeFieldset(),
                        $this->scopePrefix . static::DATA_SCOPE_SIZETYPE => $this->getSizeTypeFieldset()
                    ],
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Related Products, Up-Sells, Cross-Sells and Color Options', 'Size Options'),
                                'collapsible' => true,
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::DATA_SCOPE,
                                'sortOrder' =>
                                    $this->getNextGroupSortOrder(
                                        $meta,
                                        self::$previousGroup,
                                        self::$sortOrder
                                    ),
                            ],
                        ],

                    ],
                ],
            ]
        );

        return $meta;
    }

    /**
     * Prepares config for the Custom type products fieldset
     *
     * @return array
     */
    protected function getCustomTypeFieldset()
    {
        $content = __(
            'Custom type products are shown to customers in addition to the item the customer is looking at.'
        );

        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Custom type Products'),
                    $this->scopePrefix . static::DATA_SCOPE_CUSTOMTYPE
                ),
                'modal' => $this->getGenericModal(
                    __('Add Custom type Products'),
                    $this->scopePrefix . static::DATA_SCOPE_CUSTOMTYPE
                ),
                static::DATA_SCOPE_CUSTOMTYPE => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_CUSTOMTYPE),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Color options  Products'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 90,
                    ],
                ],
            ]
        ];
    }

 /**
     * Prepares config for the Custom type products fieldset
     *
     * @return array
     */
    protected function getSizeTypeFieldset()
    {
        $content = __(
            'Size type products are shown to customers in addition to the item the customer is looking at.'
        );

        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Size option Products'),
                    $this->scopePrefix . static::DATA_SCOPE_SIZETYPE
                ),
                'modal' => $this->getGenericModal(
                    __('Add Size option Products'),
                    $this->scopePrefix . static::DATA_SCOPE_SIZETYPE
                ),
                static::DATA_SCOPE_SIZETYPE => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_SIZETYPE),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Size option Products'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 90,
                    ],
                ],
            ]
        ];
    }
	
	
	
    /**
     * Retrieve all data scopes
     *
     * @return array
     */
    protected function getDataScopes()
    {
        return [
            static::DATA_SCOPE_RELATED,
            static::DATA_SCOPE_CROSSSELL,
            static::DATA_SCOPE_UPSELL,
            static::DATA_SCOPE_CUSTOMTYPE,
            static::DATA_SCOPE_SIZETYPE
        ];
    }
}