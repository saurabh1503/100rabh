<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Plugins;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\Indexer\IndexerRegistry;
use Manadev\Core\Logger;

class ProductAttributeModel {
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var IndexerRegistry
     */
    protected $indexerRegistry;

    public function __construct(Logger $logger, IndexerRegistry $indexerRegistry) {
        $this->logger = $logger;
        $this->indexerRegistry = $indexerRegistry;
    }

    public function beforeAfterSave(Attribute $attribute) {
        $self = $this;

        /** @noinspection PhpParamsInspection */
        $attribute->getResource()->addCommitCallback(function() use($self, $attribute) {
            $indexer = $self->indexerRegistry->get('mana_filter_attribute');
            if (!$indexer->isScheduled()) {
                $indexer->reindexRow($attribute->getId());
            }
        });
    }
}