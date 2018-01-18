<?php

namespace Boolfly\ProductRelation\Ui\DataProvider\Product\Related;

use Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider;

/**
 * Class CustomTypeDataProvider
 */
class SizeTypeDataProvider extends AbstractDataProvider
{
    /**
     * {@inheritdoc
     */
    protected function getLinkType()
    {
        return 'sizetype';
    }
}
