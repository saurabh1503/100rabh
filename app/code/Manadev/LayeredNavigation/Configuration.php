<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Configuration {
    const PRICE_RANGE_CALCULATION_METHOD = 'catalog/layered_navigation/price_range_calculation';

    const FILTER_INDEX_QUERY_LOGGING = 'mana_core/log/filter_index_queries';

    const DEFAULT_DROPDOWN_TEMPLATE = 'mana_layered_navigation/default_templates/dropdown';
    const DEFAULT_SWATCH_TEMPLATE = 'mana_layered_navigation/default_templates/swatch';
    const DEFAULT_DECIMAL_TEMPLATE = 'mana_layered_navigation/default_templates/decimal';
    const DEFAULT_PRICE_TEMPLATE = 'mana_layered_navigation/default_templates/price';
    const DEFAULT_CATEGORY_TEMPLATE = 'mana_layered_navigation/default_templates/category';

    const DEFAULT_SHOW_IN_MAIN_SIDEBAR = 'mana_layered_navigation/default_positions/in_main_sidebar';
    const DEFAULT_SHOW_IN_ADDITIONAL_SIDEBAR = 'mana_layered_navigation/default_positions/in_additional_sidebar';
    const DEFAULT_SHOW_ABOVE_PRODUCTS = 'mana_layered_navigation/default_positions/above_products';
    const DEFAULT_SHOW_ON_MOBILE = 'mana_layered_navigation/default_positions/on_mobile';

    const SHOW_APPLIED_FILTER = 'mana_layered_navigation/show_applied_filter/%s';

    const CALCULATE_SLIDER_MIN_MAX_BASED_ON = "mana_layered_navigation/slider/calculate_slider_min_max_based_on";
    const SLIDER_STYLE = 'mana_layered_navigation/slider/style';
    const MOBILE_SLIDER_STYLE = 'mana_layered_navigation/slider/style_mobile';
    const IS_SLIDER_INLINE_DROPDOWN_MENU = 'mana_layered_navigation/slider/is_slider_inline_in_dropdown_menu';

    const HIDE_FILTERS_WITH_SINGLE_VISIBLE_ITEM = 'mana_layered_navigation/other/hide_filters_with_single_visible_item';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getDefaultDropdownTemplate() {
        return $this->scopeConfig->getValue(static::DEFAULT_DROPDOWN_TEMPLATE);
    }

    public function getDefaultSwatchTemplate() {
        return $this->scopeConfig->getValue(static::DEFAULT_SWATCH_TEMPLATE);
    }

    public function getDefaultDecimalTemplate() {
        return $this->scopeConfig->getValue(static::DEFAULT_DECIMAL_TEMPLATE);
    }

    public function getDefaultPriceTemplate() {
        return $this->scopeConfig->getValue(static::DEFAULT_PRICE_TEMPLATE);
    }

    public function getDefaultCategoryTemplate() {
        return $this->scopeConfig->getValue(static::DEFAULT_CATEGORY_TEMPLATE);
    }

    public function getDefaultShowInMainSidebar() {
        return $this->scopeConfig->isSetFlag(static::DEFAULT_SHOW_IN_MAIN_SIDEBAR);
    }

    public function getDefaultShowInAdditionalSidebar() {
        return $this->scopeConfig->isSetFlag(static::DEFAULT_SHOW_IN_ADDITIONAL_SIDEBAR);
    }

    public function getDefaultShowAboveProducts() {
        return $this->scopeConfig->isSetFlag(static::DEFAULT_SHOW_ABOVE_PRODUCTS);
    }

    public function getDefaultShowOnMobile() {
        return $this->scopeConfig->isSetFlag(static::DEFAULT_SHOW_ON_MOBILE);
    }

    public function isFilterIndexQueryLoggingEnabled() {
        return $this->scopeConfig->isSetFlag(static::FILTER_INDEX_QUERY_LOGGING);
    }

    public function getPriceRangeCalculationMethod() {
        return $this->scopeConfig->getValue(static::PRICE_RANGE_CALCULATION_METHOD, ScopeInterface::SCOPE_STORE);
    }

    public function isAppliedFilterVisible($position) {
        $configKey = sprintf(self::SHOW_APPLIED_FILTER, $position);
        return $this->scopeConfig->isSetFlag($configKey, ScopeInterface::SCOPE_STORE);
    }

    public function getCalculateSliderMinMaxBasedOn() {
        return $this->scopeConfig->getValue(static::CALCULATE_SLIDER_MIN_MAX_BASED_ON);
    }

    public function getSliderStyle() {
        return $this->scopeConfig->getValue(static::SLIDER_STYLE);
    }

    public function getSliderStyleForMobile() {
        return $this->scopeConfig->getValue(static::MOBILE_SLIDER_STYLE);
    }

    public function getIsSliderInlineInDropdownMenu() {
        return $this->scopeConfig->getValue(self::IS_SLIDER_INLINE_DROPDOWN_MENU);
    }

    public function hideFiltersWithSingleVisibleItem() {
        return $this->scopeConfig->isSetFlag(static::HIDE_FILTERS_WITH_SINGLE_VISIBLE_ITEM);
    }
}