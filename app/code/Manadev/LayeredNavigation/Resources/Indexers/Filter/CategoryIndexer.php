<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Resources\Indexers\Filter;

use Magento\Framework\Model\ResourceModel\Db;
use Manadev\LayeredNavigation\Configuration;
use Manadev\LayeredNavigation\Contracts\FilterIndexer;
use Zend_Db_Expr;

class CategoryIndexer extends Db\AbstractDb implements FilterIndexer {
    /**
     * @var IndexerScope
     */
    protected $scope;

    /**
     * @var Configuration
     */
    protected $configuration;

    public function __construct(Db\Context $context,
        Configuration $configuration, IndexerScope $scope, $resourcePrefix = null)
    {
        parent::__construct($context, $resourcePrefix);
        $this->scope = $scope;
        $this->configuration = $configuration;
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
     * Returns array of store configuration paths which are used in `index`
     * method of this data source
     * @return string[]
     */
    public function getUsedStoreConfigPaths() {
        return [
            Configuration::DEFAULT_SHOW_IN_MAIN_SIDEBAR,
            Configuration::DEFAULT_SHOW_IN_ADDITIONAL_SIDEBAR,
            Configuration::DEFAULT_SHOW_ABOVE_PRODUCTS,
            Configuration::DEFAULT_SHOW_ON_MOBILE,
        ];
    }


    /**
     * Inserts or updates records in `mana_filter` table on global level
     * @param array $changes
     * @return \Magento\Framework\DB\Select
     */
    public function index($changes = ['all']) {
        $db = $this->getConnection();

        if (empty($changes['load_defaults'])) {
            $fields = [
                'edit_id' => new Zend_Db_Expr("`fge`.`id`"),
                'store_id' => new Zend_Db_Expr("0"),
                'is_deleted' => new Zend_Db_Expr("0"),
                'unique_key' => new Zend_Db_Expr("'category'"),
                'param_name' => new Zend_Db_Expr("COALESCE(`fge`.`param_name`, 'cat')"),
                'type' => new Zend_Db_Expr("'category'"),
    
                'title' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`title`, ?)", __('Category'))),
                'position' => new Zend_Db_Expr("COALESCE(`fge`.`position`, -1)"),
                'template' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`template`, ?)", $this->configuration->getDefaultCategoryTemplate())),
                'show_in_main_sidebar' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`show_in_main_sidebar`, ?)", $this->configuration->getDefaultShowInMainSidebar())),
                'show_in_additional_sidebar' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`show_in_additional_sidebar`, ?)", $this->configuration->getDefaultShowInAdditionalSidebar())),
                'show_above_products' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`show_above_products`, ?)", $this->configuration->getDefaultShowAboveProducts())),
                'show_on_mobile' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`show_on_mobile`, ?)", $this->configuration->getDefaultShowOnMobile())),
                'is_enabled_in_categories' => new Zend_Db_Expr("COALESCE(`fge`.`is_enabled_in_categories`, 1)"),
                'is_enabled_in_search' => new Zend_Db_Expr("COALESCE(`fge`.`is_enabled_in_search`, 1)"),
                'minimum_product_count_per_option' => new Zend_Db_Expr("1"),
                'hide_filter_with_single_visible_item' => new Zend_Db_Expr($db->quoteInto("COALESCE(`fge`.`hide_filter_with_single_visible_item`, ?)", $this->configuration->hideFiltersWithSingleVisibleItem())),
            ];
        }
        else {
            $fields = [
                'edit_id' => new Zend_Db_Expr("NULL"),
                'store_id' => new Zend_Db_Expr("0"),
                'is_deleted' => new Zend_Db_Expr("0"),
                'unique_key' => new Zend_Db_Expr("'category'"),
                'param_name' => new Zend_Db_Expr("'cat'"),
                'type' => new Zend_Db_Expr("'category'"),
    
                'title' => new Zend_Db_Expr($db->quoteInto("?", __('Category'))),
                'position' => new Zend_Db_Expr("-1"),
                'template' => new Zend_Db_Expr($db->quoteInto("?", $this->configuration->getDefaultCategoryTemplate())),
                'show_in_main_sidebar' => new Zend_Db_Expr($db->quoteInto("?", $this->configuration->getDefaultShowInMainSidebar())),
                'show_in_additional_sidebar' => new Zend_Db_Expr($db->quoteInto("?", $this->configuration->getDefaultShowInAdditionalSidebar())),
                'show_above_products' => new Zend_Db_Expr($db->quoteInto("?", $this->configuration->getDefaultShowAboveProducts())),
                'show_on_mobile' => new Zend_Db_Expr($db->quoteInto("?", $this->configuration->getDefaultShowOnMobile())),
                'is_enabled_in_categories' => new Zend_Db_Expr("1"),
                'is_enabled_in_search' => new Zend_Db_Expr("1"),
                'minimum_product_count_per_option' => new Zend_Db_Expr("1"),
                'hide_filter_with_single_visible_item' => new Zend_Db_Expr($db->quoteInto("?", $this->configuration->hideFiltersWithSingleVisibleItem())),
            ];
        }
            
        $select = $db->select()
            ->distinct()
            ->from(['s' => $this->getTable('store')], null)
            ->joinLeft(['fge' => $this->getTable('mana_filter_edit')],
                "`fge`.`type` = 'category' AND `fge`.`store_id` = 0", null)
            ->where("`s`.`store_id` = 0")
            ->columns($fields);

        if ($whereClause = $this->scope->limitCategoryIndexing($changes, $fields)) {
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