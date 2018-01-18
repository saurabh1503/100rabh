<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Resources\Indexers;

use Exception;
use Magento\Framework\Model\ResourceModel\Db;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Manadev\Core\Exceptions\NotImplemented;
use Manadev\Core\QueryLogger;
use Manadev\LayeredNavigation\Models\Filter;
use Manadev\LayeredNavigation\Registries\FilterIndexers\PrimaryFilterIndexers;
use Manadev\LayeredNavigation\Registries\FilterIndexers\SecondaryFilterIndexers;
use Psr\Log\LoggerInterface as Logger;
use Manadev\LayeredNavigation\Configuration;
use Manadev\LayeredNavigation\Resources\Indexers\Filter\IndexerScope;
use Zend_Db_Expr;

class FilterIndexer extends Db\AbstractDb {
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var PrimaryFilterIndexers
     */
    protected $primaryFilterIndexers;
    /**
     * @var IndexerScope
     */
    protected $scope;
    /**
     * @var Configuration
     */
    protected $configuration;
    /**
     * @var QueryLogger
     */
    protected $queryLogger;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeList;
    /**
     * @var SecondaryFilterIndexers
     */
    protected $secondaryFilterIndexers;

    public function __construct(Db\Context $context, StoreManagerInterface $storeManager, PrimaryFilterIndexers $primaryFilterIndexers,
        SecondaryFilterIndexers $secondaryFilterIndexers, IndexerScope $scope, Configuration $configuration, QueryLogger $queryLogger,
        Logger $logger, \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        $resourcePrefix = null)
    {
        parent::__construct($context, $resourcePrefix);

        $this->storeManager = $storeManager;
        $this->primaryFilterIndexers = $primaryFilterIndexers;
        $this->scope = $scope;
        $this->configuration = $configuration;
        $this->queryLogger = $queryLogger;
        $this->logger = $logger;
        $this->cacheTypeList = $cacheTypeList;
        $this->secondaryFilterIndexers = $secondaryFilterIndexers;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_setMainTable('mana_filter');
    }

    /**
     * Indexes all filter settings on global and store level, depending on
     * `$storeId` parameter.
     *
     * @param int $storeId If 0, global and store level settings are indexed,
     *                     otherwise only settings on specified store level
     *                     are indexed.
     * @param bool $useTransaction
     * @throws Exception
     */
    public function reindexAll($storeId = 0, $useTransaction = true) {
        $this->index(['all', 'store' => $storeId], $useTransaction);
    }

    /**
     * Called when attribute is changed. Indexes filters settings inherited
     * from specified attribute on global and store level, depending on
     * `$storeId` parameter.
     *
     * @param array|bool $ids
     * @param int $storeId If 0, global and store level settings are indexed,
     *                     otherwise only settings on specified store level
     *                     are indexed.
     * @param bool $useTransaction
     * @throws Exception
     */
    public function reindexChangedAttributes($ids = false, $storeId = 0,
        $useTransaction = true)
    {
        $this->index(['attributes' => $ids, 'store' => $storeId],
            $useTransaction);
    }

    /**
     * Called when filter is changed. Indexes settings of specified filter on
     * global and store level, depending on `$storeId` parameter.
     *
     * @param int[] $ids
     * @param int $storeId If 0, global and store level settings are indexed,
     *                     otherwise only settings on specified store level
     *                     are indexed.
     * @param bool $useTransaction
     * @throws Exception
     */
    public function reindexChangedFilters($ids, $storeId = 0,
        $useTransaction = true)
    {
        $this->index(['filters' => $ids, 'store' => $storeId], $useTransaction);
    }

    /**
     * @param $model Filter
     * @return array
     */
    public function loadDefaults($model) {
        $result = [];
        $changes = [
            'filters' => [$model->getData('filter_id') => "'" . $model->getData('unique_key') . "'"],
            'store' => $model->getData('store_id'),
            'load_defaults' => true,
        ];

        if (!$model->getData('store_id')) {
            foreach ($this->primaryFilterIndexers->getList() as $indexer) {
                $this->mergeDefaults($result, $indexer->index($changes));
            }

            foreach ($this->secondaryFilterIndexers->getList() as $indexer) {
                $this->mergeDefaults($result, $indexer->index($changes));
            }
        }
        else {
            $this->mergeDefaults($result, $this->indexForStore($this->storeManager->getStore($changes['store']), $changes));
        }

        return $model->getResource()->filterData($model, $result);
    }


    protected function mergeDefaults(&$result, $select) {
        $db = $this->getConnection();

        if (!$select) {
            return;
        }

        $data = $db->fetchRow($select);
        if (!empty($data)) {
            $result = array_merge($result, $data);
        }
    }

    protected function index($changes = ['all'], $useTransaction = true) {
        if ($this->configuration->isFilterIndexQueryLoggingEnabled()) {
            $this->queryLogger->begin('filter-index');
        }
        // Clear config cache if config is not set
        if(is_null($this->configuration->getDefaultShowInMainSidebar())) {
            $this->cacheTypeList->cleanType('config');
            throw new Exception('Manadev_LayeredNavigation config is not yet set. Please try again.');
        }

        $db = $this->getConnection();

        if ($useTransaction) {
            $db->beginTransaction();
        }

        try {
            if (empty($changes['store'])) {
                $this->markGlobalRowsAsDeleted($changes);

                foreach($this->primaryFilterIndexers->getList() as $indexer) {
                    $indexer->index($changes);
                }

                foreach($this->secondaryFilterIndexers->getList() as $indexer) {
                    $indexer->index($changes);
                }

                $this->deleteRowsMarkedForDeletion($changes);

                $this->assignGlobalIds($changes);

                foreach($this->storeManager->getStores() as $store) {
                    $this->indexForStore($store, $changes);
                }
            }
            else {
                $this->indexForStore($this->storeManager->getStore($changes['store']), $changes);
            }

            if ($useTransaction) {
                $db->commit();
            }
            if ($this->configuration->isFilterIndexQueryLoggingEnabled()) {
                $this->queryLogger->end('filter-index');
            }
        }
        catch (Exception $e) {
            $this->logger->critical($e);
            if ($useTransaction) {
                $db->rollBack();
            }
            if ($this->configuration->isFilterIndexQueryLoggingEnabled()) {
                $this->queryLogger->end('filter-index');
            }

            throw $e;
        }
    }

    protected function markGlobalRowsAsDeleted($changes) {
        $db = $this->getConnection();

        $db->update($this->getMainTable(), ['is_deleted' => 1],
            $this->scope->limitMarkingForDeletion($changes));
    }

    protected function deleteRowsMarkedForDeletion($changes) {
        $db = $this->getConnection();

        $db->delete($this->getMainTable(), $this->scope->limitDeletion($changes));
    }

    protected function assignGlobalIds($changes) {
        $db = $this->getConnection();

        $db->update($this->getMainTable(), ['filter_id' => new Zend_Db_Expr("`id`")],
            $this->scope->limitIdAssignment($changes));
    }

    /**
     * @param Store $store
     * @param array $changes
     * @return \Magento\Framework\DB\Select
     */
    protected function indexForStore($store, $changes = ['all']) {
        $db = $this->getConnection();

        if (empty($changes['load_defaults'])) {
            $fields = [
                'edit_id' => new Zend_Db_Expr("`fse`.`id`"),
                'filter_id' => new Zend_Db_Expr("`fg`.`id`"),
                'store_id' => new Zend_Db_Expr($store->getId()),
                'is_deleted' => new Zend_Db_Expr("0"),
                'attribute_id' => new Zend_Db_Expr("`fg`.`attribute_id`"),
                'attribute_code' => new Zend_Db_Expr("`fg`.`attribute_code`"),
                'swatch_input_type' => new Zend_Db_Expr("`fg`.`swatch_input_type`"),
                'unique_key' => new Zend_Db_Expr("CONCAT(`fg`.`unique_key`, '-{$store->getId()}')"),
                'param_name' => new Zend_Db_Expr("COALESCE(`fse`.`param_name`, `fg`.`param_name`)"),
                'type' => new Zend_Db_Expr("`fg`.`type`"),

                'title' => new Zend_Db_Expr("COALESCE(`fse`.`title`, `al`.`value`, `fg`.`title`)"),
                'position' => new Zend_Db_Expr("COALESCE(`fse`.`position`, `fg`.`position`)"),
                'template' => new Zend_Db_Expr("COALESCE(`fse`.`template`, `fg`.`template`)"),
                'show_in_main_sidebar' => new Zend_Db_Expr("COALESCE(`fse`.`show_in_main_sidebar`, `fg`.`show_in_main_sidebar`)"),
                'show_in_additional_sidebar' => new Zend_Db_Expr("COALESCE(`fse`.`show_in_additional_sidebar`, `fg`.`show_in_additional_sidebar`)"),
                'show_above_products' => new Zend_Db_Expr("COALESCE(`fse`.`show_above_products`, `fg`.`show_above_products`)"),
                'show_on_mobile' => new Zend_Db_Expr("COALESCE(`fse`.`show_on_mobile`, `fg`.`show_on_mobile`)"),
                'is_enabled_in_categories' => new Zend_Db_Expr("COALESCE(`fse`.`is_enabled_in_categories`, `fg`.`is_enabled_in_categories`)"),
                'is_enabled_in_search' => new Zend_Db_Expr("COALESCE(`fse`.`is_enabled_in_search`, `fg`.`is_enabled_in_search`)"),
                'minimum_product_count_per_option' => new Zend_Db_Expr("COALESCE(`fse`.`minimum_product_count_per_option`,
                    `fg`.`minimum_product_count_per_option`)"),

                'calculate_slider_min_max_based_on' => new Zend_Db_Expr("COALESCE(`fse`.`calculate_slider_min_max_based_on`,
                    `fg`.`calculate_slider_min_max_based_on`)"),
                'number_format' => new Zend_Db_Expr("COALESCE(`fse`.`number_format`, `fg`.`number_format`)"),
                'decimal_digits' => new Zend_Db_Expr("COALESCE(`fse`.`decimal_digits`, `fg`.`decimal_digits`)"),
                'is_two_number_formats' => new Zend_Db_Expr("COALESCE(`fse`.`is_two_number_formats`, `fg`.`is_two_number_formats`)"),
                'use_second_number_format_on' => new Zend_Db_Expr("COALESCE(`fse`.`use_second_number_format_on`, `fg`.`use_second_number_format_on`)"),
                'second_number_format' => new Zend_Db_Expr("COALESCE(`fse`.`second_number_format`, `fg`.`second_number_format`)"),
                'second_decimal_digits' => new Zend_Db_Expr("COALESCE(`fse`.`second_decimal_digits`, `fg`.`second_decimal_digits`)"),
                'show_thousand_separator' => new Zend_Db_Expr("COALESCE(`fse`.`show_thousand_separator`, `fg`.`show_thousand_separator`)"),
                'is_slide_on_existing_values' => new Zend_Db_Expr("COALESCE(`fse`.`is_slide_on_existing_values`, `fg`.`is_slide_on_existing_values`)"),
                'is_manual_range' => new Zend_Db_Expr("COALESCE(`fse`.`is_manual_range`, `fg`.`is_manual_range`)"),
                'slider_style' => new Zend_Db_Expr("COALESCE(`fse`.`slider_style`, `fg`.`slider_style`)"),
                'min_max_role' => new Zend_Db_Expr("COALESCE(`fse`.`min_max_role`, `fg`.`min_max_role`)"),
                'min_slider_code' => new Zend_Db_Expr("COALESCE(`fse`.`min_slider_code`, `fg`.`min_slider_code`)"),
                'hide_filter_with_single_visible_item' => new Zend_Db_Expr("COALESCE(`fse`.`hide_filter_with_single_visible_item`, `fg`.`hide_filter_with_single_visible_item`)"),
            ];
        }
        else {
            $fields = [
                'edit_id' => new Zend_Db_Expr("NULL"),
                'filter_id' => new Zend_Db_Expr("`fg`.`id`"),
                'store_id' => new Zend_Db_Expr($store->getId()),
                'is_deleted' => new Zend_Db_Expr("0"),
                'attribute_id' => new Zend_Db_Expr("`fg`.`attribute_id`"),
                'attribute_code' => new Zend_Db_Expr("`fg`.`attribute_code`"),
                'swatch_input_type' => new Zend_Db_Expr("`fg`.`swatch_input_type`"),
                'unique_key' => new Zend_Db_Expr("CONCAT(`fg`.`unique_key`, '-{$store->getId()}')"),
                'param_name' => new Zend_Db_Expr("`fg`.`param_name`"),
                'type' => new Zend_Db_Expr("`fg`.`type`"),

                'title' => new Zend_Db_Expr("COALESCE(`al`.`value`, `fg`.`title`)"),
                'position' => new Zend_Db_Expr("`fg`.`position`"),
                'template' => new Zend_Db_Expr("`fg`.`template`"),
                'show_in_main_sidebar' => new Zend_Db_Expr("`fg`.`show_in_main_sidebar`"),
                'show_in_additional_sidebar' => new Zend_Db_Expr("`fg`.`show_in_additional_sidebar`"),
                'show_above_products' => new Zend_Db_Expr("`fg`.`show_above_products`"),
                'show_on_mobile' => new Zend_Db_Expr("`fg`.`show_on_mobile`"),
                'is_enabled_in_categories' => new Zend_Db_Expr("`fg`.`is_enabled_in_categories`"),
                'is_enabled_in_search' => new Zend_Db_Expr("`fg`.`is_enabled_in_search`"),
                'minimum_product_count_per_option' => new Zend_Db_Expr("`fg`.`minimum_product_count_per_option`"),

                'calculate_slider_min_max_based_on' => new Zend_Db_Expr("`fg`.`calculate_slider_min_max_based_on`"),
                'number_format' => new Zend_Db_Expr("`fg`.`number_format`"),
                'decimal_digits' => new Zend_Db_Expr("`fg`.`decimal_digits`"),
                'is_two_number_formats' => new Zend_Db_Expr("`fg`.`is_two_number_formats`"),
                'use_second_number_format_on' => new Zend_Db_Expr("`fg`.`use_second_number_format_on`"),
                'second_number_format' => new Zend_Db_Expr("`fg`.`second_number_format`"),
                'second_decimal_digits' => new Zend_Db_Expr("`fg`.`second_decimal_digits`"),
                'show_thousand_separator' => new Zend_Db_Expr("`fg`.`show_thousand_separator`"),
                'is_slide_on_existing_values' => new Zend_Db_Expr("`fg`.`is_slide_on_existing_values`"),
                'is_manual_range' => new Zend_Db_Expr("`fg`.`is_manual_range`"),
                'slider_style' => new Zend_Db_Expr("`fg`.`slider_style`"),
                'min_max_role' => new Zend_Db_Expr("`fg`.`min_max_role`"),
                'min_slider_code' => new Zend_Db_Expr("`fg`.`min_slider_code`"),
                'hide_filter_with_single_visible_item' => new Zend_Db_Expr("`fg`.`hide_filter_with_single_visible_item`"),
            ];
        }

        $select = $db->select()
            ->distinct()
            ->from(['fg' => $this->getTable('mana_filter')], null)
            ->joinLeft(['fse' => $this->getTable('mana_filter_edit')],
                $db->quoteInto("`fse`.`filter_id` = `fg`.`id` AND `fse`.`store_id` = ?", $store->getId()), null)
            ->joinLeft(['a' => $this->getTable('eav_attribute')], "`a`.`attribute_id` = `fg`.`attribute_id`", null)
            ->joinLeft(['al' => $this->getTable('eav_attribute_label')],
                $db->quoteInto("`al`.`attribute_id` = `fg`.`attribute_id` AND `al`.`store_id` = ?", $store->getId()), null)
            ->columns($fields);

        if ($whereClause = $this->scope->limitStoreLevelIndexing($changes, $fields)) {
            $select->where($whereClause);
        }

        if (empty($changes['load_defaults'])) {
            // convert SELECT into UPDATE which acts as INSERT on DUPLICATE unique keys
            $sql = $select->insertFromSelect($this->getMainTable(), array_keys($fields));

            // run the statement
            $db->query($sql);
        }

        return $select;
    }
}