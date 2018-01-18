<?php
/**
 * Same as obsolete_methods.php, but specific to Magento EE
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    ['_filterIndexData', 'Magento\Solr\Model\Adapter\AbstractAdapter'],
    ['getSearchTextFields', 'Magento\Solr\Model\Adapter\AbstractAdapter'],
    ['addAppliedRuleFilter', 'Magento\Banner\Model\Resource\Catalogrule\Collection'],
    ['addBannersFilter', 'Magento\Banner\Model\Resource\Catalogrule\Collection'],
    ['addBannersFilter', 'Magento\Banner\Model\Resource\Salesrule\Collection'],
    ['addCategoryFilter', 'Magento\Solr\Model\Catalog\Layer\Filter\Category'],
    ['addCustomerSegmentFilter', 'Magento\Banner\Model\Resource\Catalogrule\Collection'],
    ['addCustomerSegmentFilter', 'Magento\Banner\Model\Resource\Salesrule\Collection'],
    ['addDashboardLink', 'Magento\Rma\Block\Link'],
    ['addRenderer', 'Magento\CustomAttributeManagement\Block\Form'],
    ['setModelName', 'Magento\Logging\Model\Event\Changes'],
    ['getModelName', 'Magento\Logging\Model\Event\Changes'],
    ['setModelId', 'Magento\Logging\Model\Event\Changes'],
    ['getModelId', 'Magento\Logging\Model\Event\Changes'],
    ['_initAction', 'Magento\AdvancedCheckout\Controller\Adminhtml\Checkout'],
    ['getEventData', 'Magento\Logging\Block\Adminhtml\Container'],
    ['getEventXForwardedIp', 'Magento\Logging\Block\Adminhtml\Container'],
    ['getEventIp', 'Magento\Logging\Block\Adminhtml\Container'],
    ['getEventError', 'Magento\Logging\Block\Adminhtml\Container'],
    ['postDispatchSystemStoreSave', 'Magento\Logging\Model\Handler\Controllers'],
    ['getUrls', 'Magento\FullPageCache\Model\Crawler'],
    ['getUrlStmt', 'Magento\FullPageCache\Model\Resource\Crawler'],
    ['_getLinkCollection', 'Magento\TargetRule\Block\Checkout\Cart\Crosssell'],
    ['getCustomerSegments', 'Magento\CustomerSegment\Model\Resource\Customer'],
    ['getRequestUri', 'Magento\FullPageCache\Model\Processor\DefaultProcessor'],
    ['_getActiveEntity', 'Magento\GiftRegistry\Controller\Index'],
    ['getActiveEntity', 'Magento\GiftRegistry\Model\Entity'],
    ['_convertDateTime', 'Magento\CatalogEvent\Model\Event'],
    ['updateStatus', 'Magento\CatalogEvent\Model\Event'],
    ['getStateText', 'Magento\GiftCardAccount\Model\Giftcardaccount'],
    ['getStoreContent', 'Magento\Banner\Model\Banner'],
    ['_searchSuggestions', 'Magento\Solr\Model\Adapter\HttpStream'],
    ['_searchSuggestions', 'Magento\Solr\Model\Adapter\PhpExtension'],
    ['updateCategoryIndexData', 'Magento\Solr\Model\Resource\Index'],
    ['updatePriceIndexData', 'Magento\Solr\Model\Resource\Index'],
    ['_changeIndexesStatus', 'Magento\Solr\Model\Indexer\Indexer'],
    ['cmsPageBlockLoadAfter', 'Magento\AdminGws\Model\Models'],
    ['increaseOrderInvoicedAmount', 'Magento\GiftCardAccount\Model\Observer'],
    ['initRewardType', 'Magento\Reward\Block\Tooltip'],
    ['initRewardType', 'Magento\Reward\Block\Tooltip\Checkout'],
    ['blockCreateAfter', 'Magento\FullPageCache\Model\Observer'],
    ['_checkViewedProducts', 'Magento\FullPageCache\Model\Observer'],
    ['invoiceSaveAfter', 'Magento\Reward\Model\Observer'],
    ['_calcMinMax', 'Magento\GiftCard\Block\Catalog\Product\Price'],
    ['_getAmounts', 'Magento\GiftCard\Block\Catalog\Product\Price'],
    ['searchSuggestions', 'Magento\Solr\Model\Client\Solr'],
    ['_registerProductsView', 'Magento\FullPageCache\Model\Container\Viewedproducts'],
    ['_getForeignKeyName', 'Magento\Framework\DB\Adapter\Oracle'],
    ['getCacheInstance', 'Magento\FullPageCache\Model\Cache'],
    ['saveCustomerSegments', 'Magento\Banner\Model\Resource\Banner'],
    ['saveOptions', 'Magento\FullPageCache\Model\Cache'],
    [
        'refreshRequestIds',
        'Magento\FullPageCache\Model\Processor',
        'Magento_FullPageCache_Model_Request_Identifier::refreshRequestIds'
    ],
    ['removeCartLink', 'Magento\PersistentHistory\Model\Observer'],
    ['resetColumns', 'Magento\Banner\Model\Resource\Salesrule\Collection'],
    ['resetSelect', 'Magento\Banner\Model\Resource\Catalogrule\Collection'],
    [
        'prepareCacheId',
        'Magento\FullPageCache\Model\Processor',
        'Magento_FullPageCache_Model_Request_Identifier::prepareCacheId'
    ],
    [
        '_getQuote',
        'Magento\AdvancedCheckout\Block\Adminhtml\Manage\Form\Coupon',
        'Magento_AdvancedCheckout_Block_Adminhtml_Manage_Form_Coupon::getQuote()'
    ],
    [
        '_getQuote',
        'Magento\GiftCardAccount\Block\Checkout\Cart\Total',
        'Magento_GiftCardAccount_Block_Checkout_Cart_Total::getQuote()'
    ],
    [
        '_getQuote',
        'Magento\GiftCardAccount\Block\Checkout\Onepage\Payment\Additional',
        'Magento_GiftCardAccount_Block_Checkout_Onepage_Payment_Additional::getQuote()'
    ],
    [
        '_getQuote',
        'Magento\GiftWrapping\Block\Checkout\Options',
        'Magento_GiftWrapping_Block_Checkout_Options::getQuote()'
    ],
    ['addCustomerSegmentRelationsToCollection', 'Magento\TargetRule\Model\Resource\Rule'],
    ['_getRuleProductsTable', 'Magento\TargetRule\Model\Resource\Rule'],
    ['getCustomerSegmentRelations', 'Magento\TargetRule\Model\Resource\Rule'],
    ['setCustomerSegmentRelations', 'Magento\TargetRule\Model\Resource\Rule'],
    ['_saveCustomerSegmentRelations', 'Magento\TargetRule\Model\Resource\Rule'],
    ['_prepareRuleProducts', 'Magento\TargetRule\Model\Resource\Rule'],
    ['getInetNtoaExpr', 'Magento\Logging\Model\Resource\Helper'],
    ['catalogCategoryIsCatalogPermissionsAllowed', 'Magento\AdminGws\Model\Models'],
    ['catalogCategoryMoveBefore', 'Magento\AdminGws\Model\Models'],
    ['catalogProductActionWithWebsitesAfter', 'Magento\AdminGws\Model\Models'],
    ['restrictCustomerRegistration', 'Magento\Invitation\Model\Observer'],
    ['restrictCustomersRegistration', 'Magento\WebsiteRestriction\Model\Observer'],
    ['checkCategoryPermissions', 'Magento\CatalogPermissions\Model\Adminhtml\Observer'],
    ['chargeById', 'Magento\GiftCardAccount\Model\Observer'],
    ['_helper', 'Magento\GiftRegistry\Model\Entity'],
    ['_getConfig', 'Magento\SalesArchive\Model\Resource\Archive'],
    ['_getCart', 'Magento\AdvancedCheckout\Model\Cart'],
    ['getMaxInvitationsPerSend', '\Magento\Invitation\Helper\Data'],
    ['getInvitationRequired', '\Magento\Invitation\Helper\Data'],
    ['getUseInviterGroup', '\Magento\Invitation\Helper\Data'],
    ['isInvitationMessageAllowed', '\Magento\Invitation\Helper\Data'],
    ['isEnabled', '\Magento\Invitation\Helper\Data'],
    ['checkMessages', '\Magento\FullPageCache\Model\Observer'],
    ['appendGiftcardAdditionalData', 'Magento\GiftCard\Model\Observer'],
    ['_getResource', 'Magento\GiftCard\Model\Attribute\Backend\Giftcard\Amount'],
    ['getNode', 'Magento\Logging\Model\Config'],
    ['isActive', 'Magento\Logging\Model\Config'],
    ['_getCallbackFunction', 'Magento\Logging\Model\Processor'],
    ['_getOrderCreateModel', 'Magento\Reward\Block\Adminhtml\Sales\Order\Create\Payment'],
    [
        'getEntityResourceModel',
        'Magento\SalesArchive\Model\Archive',
        'Magento_SalesArchive_Model_ArchivalList::getResource'
    ],
    [
        'detectArchiveEntity',
        'Magento\SalesArchive\Model\Archive',
        'Magento_SalesArchive_Model_ArchivalList::getEntityByObject'
    ],
    ['applyIndexChanges', 'Magento\Solr\Model\Observer'],
    ['holdCommit', 'Magento\Solr\Model\Observer'],
    ['getDefaultMenuLayoutCode', 'Magento\VersionsCms\Model\Hierarchy\Config'],
    ['coreBlockAbstractToHtmlBefore', 'Magento\PricePermissions\Model\Observer', 'viewBlockAbstractToHtmlBefore'],
    [
        'coreBlockAbstractToHtmlBefore',
        'Magento\PromotionPermissions\Model\Observer',
        'viewBlockAbstractToHtmlBefore'
    ],
    ['getServerIoDriver', '\Magento\ScheduledImportExport\Model\Scheduled\Operation'],
    ['_isConfigured', '\Magento\AdvancedCheckout\Model\Cart'],
    ['_getIsAllowedGrant', 'Magento\CatalogPermissions\Helper\Data', 'isAllowedGrant'],
    [
        'isEnabled',
        'Magento\CatalogPermissions\Helper\Data',
        'Magento\CatalogPermissions\App\ConfigInterface::isEnabled'
    ],
    [
        'addIndexToProductCount',
        'Magento\CatalogPermissions\Model\Permission\Index',
        'addIndexToProductCollection'
    ],
    ['applyPriceGrantToPriceIndex', 'Magento\CatalogPermissions\Model\Permission\Index'],
    [
        'addIndexToProductCount',
        'Magento\CatalogPermissions\Model\Resource\Permission\Index',
        'addIndexToProductCollection'
    ],
    [
        'reindex',
        'Magento\CatalogPermissions\Model\Resource\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\AbstractAction::reindex'
    ],
    ['reindexProducts', 'Magento\CatalogPermissions\Model\Resource\Permission\Index'],
    [
        'reindexProductsStandalone',
        'Magento\CatalogPermissions\Model\Resource\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\AbstractAction::populateProductIndex'
    ],
    [
        '_getConfigGrantDbExpr',
        'Magento\CatalogPermissions\Model\Resource\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\AbstractAction::getConfigGrantDbExpr'
    ],
    ['_getStoreIds', 'Magento\CatalogPermissions\Model\Resource\Permission\Index'],
    ['applyPriceGrantToPriceIndex', 'Magento\CatalogPermissions\Model\Resource\Permission\Index'],
    ['_beginInsert', 'Magento\CatalogPermissions\Model\Resource\Permission\Index'],
    ['_commitInsert', 'Magento\CatalogPermissions\Model\Resource\Permission\Index'],
    ['_insert', 'Magento\CatalogPermissions\Model\Resource\Permission\Index'],
    [
        '_inheritCategoryPermission',
        'Magento\CatalogPermissions\Model\Resource\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\AbstractAction::prepareInheritedCategoryIndexPermissions'
    ],
    [
        'reindexAll',
        'Magento\CatalogPermissions\Model\Resource\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\AbstractAction::reindex'
    ],
    [
        'reindex',
        'Magento\CatalogPermissions\Model\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\Category\Action\Rows::execute'
    ],
    ['reindexProducts', 'Magento\CatalogPermissions\Model\Permission\Index'],
    ['getName', 'Magento\CatalogPermissions\Model\Permission\Index'],
    ['_registerEvent', 'Magento\CatalogPermissions\Model\Permission\Index'],
    ['_processEvent', 'Magento\CatalogPermissions\Model\Permission\Index'],
    [
        'reindexProductsStandalone',
        'Magento\CatalogPermissions\Model\Permission\Index',
        'Magento\CatalogPermissions\Model\Indexer\Product\Action\Rows::execute'
    ],
    ['reindex', 'Magento\CatalogPermissions\Model\Adminhtml\Observer'],
    ['saveCategoryPermissions', 'Magento\CatalogPermissions\Model\Adminhtml\Observer'],
    ['reindexCategoryPermissionOnMove', 'Magento\CatalogPermissions\Model\Adminhtml\Observer'],
    ['reindexPermissions', 'Magento\CatalogPermissions\Model\Adminhtml\Observer'],
    ['reindexAfterProductAssignedWebsite', 'Magento\CatalogPermissions\Model\Adminhtml\Observer'],
    ['saveProductPermissionIndex', 'Magento\CatalogPermissions\Model\Adminhtml\Observer'],
    ['reindexProducts', 'Magento\CatalogPermissions\Model\Adminhtml\Observer'],
    ['getCacheIdTags', 'Magento\CatalogEvent\Model\Event'],
    ['_compareSortOrder', 'Magento\Rma\Block\Returns\Create'],
    ['getTierPriceHtml', 'Magento\AdvancedCheckout\Block\Sku\Products\Info'],
    ['sendNewRmaEmail', 'Magento\Rma\Model\Rma', 'Magento\Rma\Model\Rma\Status\History::sendNewRmaEmail'],
    ['sendAuthorizeEmail', 'Magento\Rma\Model\Rma', 'Magento\Rma\Model\Rma\Status\History::sendAuthorizeEmail'],
    ['_sendRmaEmailWithItems', 'Magento\Rma\Model\Rma', 'Magento\Rma\Model\Rma\Status\History::_sendRmaEmailWithItems'],
    [
        'beforeRebuildIndex',
        'Magento\Solr\Model\Plugin\FulltextIndexRebuild',
        'Magento\Solr\Model\Plugin\FulltextIndexRebuild::beforeExecuteFull'
    ],
    [
        'afterRebuildIndex',
        'Magento\Solr\Model\Plugin\FulltextIndexRebuild',
        'Magento\Solr\Model\Plugin\FulltextIndexRebuild::afterExecuteFull'
    ],
    [
        'reindexAll',
        'Magento\ScheduledImportExport\Model\Import',
        'Magento\ImportExport\Model\Import::invalidateIndex'
    ],
    ['_beforeLoad', 'Magento\Solr\Model\Resource\Collection'],
    ['_afterLoad', 'Magento\Solr\Model\Resource\Collection'],
    ['setEngine', 'Magento\Solr\Model\Resource\Collection'],
    ['customerGroupSaveAfter', 'Magento\Solr\Model\Observer'],
    ['saveStoreIdsBeforeScopeDelete', 'Magento\Solr\Model\Observer'],
    ['clearIndexForStores', 'Magento\Solr\Model\Observer'],
    ['runFulltextReindexAfterPriceReindex', 'Magento\Solr\Model\Observer'],
    ['_beforeLoad', 'Magento\Search\Model\Resource\Collection'],
    ['_afterLoad', 'Magento\Search\Model\Resource\Collection'],
    ['setEngine', 'Magento\Search\Model\Resource\Collection'],
    ['customerGroupSaveAfter', 'Magento\Search\Model\Observer'],
    ['saveStoreIdsBeforeScopeDelete', 'Magento\Search\Model\Observer'],
    ['clearIndexForStores', 'Magento\Search\Model\Observer'],
    ['runFulltextReindexAfterPriceReindex', 'Magento\Search\Model\Observer'],
    ['getDateModel', '\Magento\ScheduledImportExport\Model\Export'],
    ['getDateModel', '\Magento\ScheduledImportExport\Model\Scheduled\Operation'],
    ['modifyExpiredQuotesCleanup', 'Magento\PersistentHistory\Model\Observer'],
    ['Magento\Solr\Model\Price\Interval::load'],
    [
        'Magento\Solr\Model\Price\Interval::loadPrevious'
    ],
    [
        'Magento\Solr\Model\Price\Interval::loadNext'
    ],
    ['getIndexer', 'Magento\CatalogPermissions\Model\Indexer\Plugin\AbstractProduct'],
    ['getIndexer', 'Magento\CatalogPermissions\Model\Indexer\Plugin\Category'],
    ['getIndexer', 'Magento\CatalogPermissions\Model\Indexer\Plugin\ConfigData'],
    ['getIndexer', 'Magento\CatalogPermissions\Model\Indexer\Plugin\CustomerGroupV1'],
    ['getIndexer', 'Magento\CatalogPermissions\Model\Indexer\Plugin\Store\AbstractPlugin'],
    ['getLockLifetime', 'Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Edit\Form'],
    ['isLockedByMe', 'Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Edit\Form'],
    ['isLockedByOther', 'Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Edit\Form'],
    ['canDragNodes', 'Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Edit\Form'],
    ['getRefreshWrappingUrl', 'Magento\GiftWrapping\Block\Adminhtml\Order\Create\Info'],
    ['limitCustomerSegments', 'Magento\AdminGws\Model\Collections'],
    ['limitPriceRules', ' Magento\AdminGws\Model\Collections'],
    ['customerSegmentSaveBefore', 'Magento\AdminGws\Model\Models'],
    ['customerSegmentDeleteBefore', 'Magento\AdminGws\Model\Models'],
    ['customerSegmentLoadAfter', 'Magento\AdminGws\Model\Models'],
    ['quoteInto', 'Magento\Reminder\Model\Resource\Rule'],
    ['_saveMatchedCustomerData', 'Magento\Reminder\Model\Resource\Rule'],
    ['_saveWebsiteIds', 'Magento\Reminder\Model\Resource\Rule'],
    ['_matchProductIds', 'Magento\TargetRule\Model\Resource\Index'],
    ['getLockAlertMessage', 'Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Edit\Form'],
    ['setAdvancedIndexFieldPrefix', 'Magento\Solr\Model\AdapterInterface'],
    ['getFieldsPrefix', 'Magento\Solr\Model\Resource\Engine'],
    ['addAllowedAdvancedIndexField', 'Magento\Solr\Model\Resource\Engine'],
    ['_isDynamicField', 'Magento\Solr\Model\Resource\Engine'],
    ['prepareDocs', 'Magento\Solr\Model\Adapter\AbstractAdapter'],
    ['_getIndexableAttributeParams', 'Magento\Solr\Model\Adapter\AbstractAdapter'],
    ['_prepareIndexData', 'Magento\Solr\Model\Adapter\AbstractAdapter'],
    ['_prepareIndexData', 'Magento\Solr\Model\Adapter\Solr\AbstractSolr'],
    ['getAttributeSolrFieldName', 'Magento\Solr\Model\Adapter\Solr\AbstractSolr'],
    ['getAttributeSolrFieldName', 'Magento\Solr\Helper\Data'],
    ['getTreeHtml', 'Magento\VersionsCms\Block\Adminhtml\Cms\Hierarchy\Widget\Chooser'],
    ['getEntityTypeId', 'Magento\Rma\Model\Rma'],
    ['getRandomString', 'Magento\AdvancedCheckout\Block\Sku\AbstractSku'],
    [
        'getWishlistShortList',
        'Magento\MultipleWishlist\Block\Behaviour',
        'Magento\MultipleWishlist\CustomerData\MultipleWishlist::getWishlistShortList'
    ],
    [
        'canCreateWishlists',
        'Magento\MultipleWishlist\Block\Behaviour',
        'Magento\MultipleWishlist\CustomerData\MultipleWishlist::canCreateWishlists'
    ],
    ['getWishlists', 'Magento\MultipleWishlist\Block\Behaviour'],
    ['getDefaultWishlist', 'Magento\MultipleWishlist\Block\Behaviour'],
    ['getAddItemUrl', 'Magento\MultipleWishlist\Block\Behaviour'],
    [
        'getBannersContent',
        'Magento\Banner\Block\Widget\Banner',
        'Move to appropriate methods in \Magento\Banner\CustomerData\Banner'
    ],
    ['getBannerIds', 'Magento\Banner\Block\Widget\Banner'],
    ['getCacheKeyInfo', 'Magento\Banner\Block\Widget\Banner'],
    ['_clearRenderedParams', 'Magento\Banner\Block\Widget\Banner'],
    ['_getRenderedParams', 'Magento\Banner\Block\Widget\Banner'],
    ['_setRenderedParam', 'Magento\Banner\Block\Widget\Banner'],
    ['renderAndGetInfo', 'Magento\Banner\Block\Widget\Banner'],
    ['getIdentities', 'Magento\Banner\Block\Widget\Banner'],
    ['getExistingBannerIdsBySpecifiedIds', 'Magento\Banner\Model\Resource\Banner'],
    ['updateFaiure', 'Magento\Pci\Model\Resource\Admin\User', 'updateFailure'],
    ['getIsCentinelValidationEnabled', 'Magento\Payment\Model\Method\Cc'],
    ['getCentinelValidator', 'Magento\Payment\Model\Method\Cc'],
    ['getCentinelValidationData', 'Magento\Payment\Model\Method\Cc'],
    ['_get3dSecureCreditCardValidation', 'Magento\Config\Test\Repository\Config'],
    ['_getVisa3dSecureValidSecondCard', 'Magento\Payment\Test\Repository\Cc'],
    ['_getVisa3dSecureInvalid', 'Magento\Payment\Test\Repository\Cc'],
    ['_getVisa3dSecureValid', 'Magento\Payment\Test\Repository\Cc'],
    [
        '_initCustomer',
        'Magento\CustomerBalance\Controller\Adminhtml\Customerbalance',
        'Magento\CustomerBalance\Controller\Adminhtml\Customerbalance::initCurrentCustomer'
    ],
    [
        'affectCmsPageRender',
        'Magento\VersionsCms\Model\Observer',
        'Magento\VersionsCms\Observer\AffectCmsPageRender::invoke'
    ],
    [
        'addCmsToTopmenuItems',
        'Magento\VersionsCms\Model\Observer',
        'Magento\VersionsCms\Observer\AddCmsToTopmenuItems::invoke'
    ],
    [
        '_isCmsNodeActive',
        'Magento\VersionsCms\Model\Observer',
        'Magento\VersionsCms\Observer\AddCmsToTopmenuItems::_isCmsNodeActive'
    ],
    [
        'cmsControllerRouterMatchBefore',
        'Magento\VersionsCms\Model\Observer',
        'Magento\VersionsCms\Observer\CmsControllerRouterMatchBefore::invoke'
    ],
    [
        'coreCopyFieldsetCustomerAccountToQuote',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetCustomerAccountToQuote::invoke'
    ],
    [
        'coreCopyFieldsetCheckoutOnepageQuoteToCustomer',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetCheckoutOnepageQuoteToCustomer::invoke'
    ],
    [
        'coreCopyFieldsetCustomerAddressToQuoteAddress',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetCustomerAddressToQuoteAddress::invoke'
    ],
    [
        'coreCopyFieldsetOrderAddressToCustomerAddress',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetOrderAddressToCustomerAddress::invoke'
    ],
    [
        'coreCopyFieldsetQuoteAddressToCustomerAddress',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetQuoteAddressToCustomerAddress::invoke'
    ],
    [
        'coreCopyFieldsetSalesConvertQuoteAddressToOrderAddress',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetSalesConvertQuoteAddressToOrderAddress::invoke'
    ],
    [
        'coreCopyFieldsetSalesConvertQuoteToOrder',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetSalesConvertQuoteToOrder::invoke'
    ],
    [
        'coreCopyFieldsetSalesCopyOrderBillingAddressToOrder',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetSalesCopyOrderBillingAddressToOrder::invoke'
    ],
    [
        'coreCopyFieldsetSalesCopyOrderShippingAddressToOrder',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetSalesCopyOrderShippingAddressToOrder::invoke'
    ],
    [
        'coreCopyFieldsetSalesCopyOrderToEdit',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\CoreCopyFieldsetSalesCopyOrderToEdit::invoke'
    ],
    [
        'enterpriseCustomerAddressAttributeDelete',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\EnterpriseCustomerAddressAttributeDelete::invoke'
    ],
    [
        'enterpriseCustomerAddressAttributeSave',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\EnterpriseCustomerAddressAttributeSave::invoke'
    ],
    [
        'enterpriseCustomerAttributeBeforeSave',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\EnterpriseCustomerAttributeBeforeSave::invoke'
    ],
    [
        'enterpriseCustomerAttributeDelete',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\EnterpriseCustomerAttributeDelete::invoke'
    ],
    [
        'enterpriseCustomerAttributeSave',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\EnterpriseCustomerAttributeSave::invoke'
    ],
    [
        'salesOrderAddressAfterLoad',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesOrderAddressAfterLoad::invoke'
    ],
    [
        'salesOrderAddressAfterSave',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesOrderAddressAfterSave::invoke'
    ],
    [
        'salesOrderAddressCollectionAfterLoad',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesOrderAddressCollectionAfterLoad::invoke'
    ],
    [
        'salesOrderAfterLoad',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesOrderAfterLoad::invoke'
    ],
    [
        'salesOrderAfterSave',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesOrderAfterSave::invoke'
    ],
    [
        'salesQuoteAddressAfterSave',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesQuoteAddressAfterSave::invoke'
    ],
    [
        'salesQuoteAddressCollectionAfterLoad',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesQuoteAddressCollectionAfterLoad::invoke'
    ],
    [
        'salesQuoteAfterLoad',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesQuoteAfterLoad::invoke'
    ],
    [
        'salesQuoteAfterSave',
        'Magento\CustomerCustomAttributes\Model\Observer',
        'Magento\CustomerCustomAttributes\Observer\SalesQuoteAfterSave::invoke'
    ],
    [
        'generateGiftCardAccounts',
        'Magento\GiftCard\Model\Observer',
        'Magento\GiftCard\Observer\GenerateGiftCardAccounts::invoke'
    ],
    [
        'initOptionRenderer',
        'Magento\GiftCard\Model\Observer',
        'Magento\GiftCard\Observer\InitOptionRenderer::invoke'
    ],
    [
        'loadAttributesAfterCollectionLoad',
        'Magento\GiftCard\Model\Observer',
        'Magento\GiftCard\Observer\LoadAttributesAfterCollectionLoad::invoke'
    ],
    [
        'setAmountsRendererInForm',
        'Magento\GiftCard\Model\Observer',
        'Magento\GiftCard\Observer\SetAmountsRendererInForm::invoke'
    ],
    [
        'updateExcludedFieldList',
        'Magento\GiftCard\Model\Observer',
        'Magento\GiftCard\Observer\UpdateExcludedFieldList::invoke'
    ],
    [
        'addPaymentGiftCardItem',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\AddPaymentGiftCardItem::invoke'
    ],
    [
        'chargeByCode',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\ChargeByCode::invoke'
    ],
    [
        'createGiftCard',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\CreateGiftCard::invoke'
    ],
    [
        'creditmemoDataImport',
        'Magento\GiftCardAccount\Model\Observer','Magento\GiftCardAccount\Observer\CreditmemoDataImport::invoke'
    ],
    [
        'extendSalesAmountExpression',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\ExtendSalesAmountExpression::invoke'
    ],
    [
        'giftcardaccountSaveAfter',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\GiftcardaccountSaveAfter::invoke'
    ],
    [
        'increaseOrderGiftCardInvoicedAmount',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\IncreaseOrderGiftCardInvoicedAmount::invoke'
    ],
    [
        'paymentDataImport',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\PaymentDataImport::invoke'
    ],
    [
        'processOrderCreationData',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\ProcessOrderCreationData::invoke'
    ],
    [
        'quoteCollectTotalsBefore',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\QuoteCollectTotalsBefore::invoke'
    ],
    [
        'quoteMergeAfter',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\QuoteMergeAfter::invoke'
    ],
    [
        'refund',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\Refund::invoke'
    ],
    [
        'returnFundsToStoreCredit',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\ReturnFundsToStoreCredit::invoke'
    ],
    [
        'revertGiftCardAccountBalance',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\RevertGiftCardAccountBalance::invoke'
    ],
    [
        'revertGiftCardsForAllOrders',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\RevertGiftCardsForAllOrders::invoke'
    ],
    [
        'salesOrderLoadAfter',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\SalesOrderLoadAfter::invoke'
    ],
    [
        'togglePaymentMethods',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\TogglePaymentMethods::invoke'
    ],
    [
        'processOrderPlace',
        'Magento\GiftCardAccount\Model\Observer',
        'Magento\GiftCardAccount\Observer\ProcessOrderPlace::invoke'
    ],
    [
        'applyEventOnQuoteItemSetProduct',
        'Magento\CatalogEvent\Model\Observer',
        'Magento\CatalogEvent\Observer\ApplyEventOnQuoteItemSetProduct::invoke'
    ],
    [
        'applyEventOnQuoteItemSetQty',
        'Magento\CatalogEvent\Model\Observer',
        'Magento\CatalogEvent\Observer\ApplyEventOnQuoteItemSetQty::invoke'
    ],
    [
        'applyEventToCategory',
        'Magento\CatalogEvent\Model\Observer',
        'Magento\CatalogEvent\Observer\ApplyEventToCategory::invoke'
    ],
    [
        'applyEventToCategoryCollection',
        'Magento\CatalogEvent\Model\Observer',
        'Magento\CatalogEvent\Observer\ApplyEventToCategoryCollection::invoke'
    ],
    [
        'applyEventToProduct',
        'Magento\CatalogEvent\Model\Observer',
        'Magento\CatalogEvent\Observer\ApplyEventToProduct::invoke'
    ],
    [
        'applyEventToProductCollection',
        'Magento\CatalogEvent\Model\Observer',
        'Magento\CatalogEvent\Observer\ApplyEventToProductCollection::invoke'
    ],
    [
        'applyIsSalableToProduct',
        'Magento\CatalogEvent\Model\Observer',
        'Magento\CatalogEvent\Observer\ApplyIsSalableToProduct::invoke'
    ],
    ['getReadAdapter', 'Magento\CatalogPermissions\Helper\Index'],
    ['getWriteAdapter', 'Magento\CatalogPermissions\Helper\Index'],
    ['getReadAdapter', 'Magento\CatalogPermissions\Model\Indexer\AbstractAction'],
    ['getWriteAdapter', 'Magento\CatalogPermissions\Model\Indexer\AbstractAction'],
    [
        'addPaymentGiftWrappingItem',
        'Magento\GiftWrapping\Model\Observer',
        'Magento\GiftWrapping\Observer\AddPaymentGiftWrappingItem::invoke'
    ],
    [
        'checkoutProcessWrappingInfo',
        'Magento\GiftWrapping\Model\Observer',
        'Magento\GiftWrapping\Observer\CheckoutProcessWrappingInfo::invoke'
    ],
    [
        'processOrderCreationData',
        'Magento\GiftWrapping\Model\Observer',
        'Magento\GiftWrapping\Observer\ProcessOrderCreationData::invoke'
    ],
    [
        'prepareGiftOptionsItems',
        'Magento\GiftWrapping\Model\Observer',
        'Magento\GiftWrapping\Observer\PrepareGiftOptionsItems::invoke'
    ],
    [
        'quoteCollectTotalsBefore',
        'Magento\GiftWrapping\Model\Observer',
        'Magento\GiftWrapping\Observer\QuoteCollectTotalsBefore::invoke'
    ],
    [
        'salesEventOrderItemToQuoteItem',
        'Magento\GiftWrapping\Model\Observer',
        'Magento\GiftWrapping\Observer\SalesEventOrderItemToQuoteItem::invoke'
    ],
    [
        'salesEventOrderToQuote',
        'Magento\GiftWrapping\Model\Observer',
        'Magento\GiftWrapping\Observer\SalesEventOrderToQuote::invoke'
    ],
    [
        'addGiftRegistryQuoteFlag',
        'Magento\GiftRegistry\Model\Observer',
        'Magento\GiftRegistry\Observer\AddGiftRegistryQuoteFlag::invoke'
    ],
    [
        'addressDataAfterLoad',
        'Magento\GiftRegistry\Model\Observer',
        'Magento\GiftRegistry\Observer\AddressDataAfterLoad::invoke'
    ],
    [
        'addressDataBeforeLoad',
        'Magento\GiftRegistry\Model\Observer',
        'Magento\GiftRegistry\Observer\AddressDataBeforeLoad::invoke'
    ],
    [
        'addressDataBeforeSave',
        'Magento\GiftRegistry\Model\Observer',
        'Magento\GiftRegistry\Observer\AddressDataBeforeSave::invoke'
    ],
    [
        'addressFormatFront',
        'Magento\GiftRegistry\Model\Observer',
        'Magento\GiftRegistry\Observer\AddressFormatFront::invoke'
    ],
    [
        'addressFormatAdmin',
        'Magento\GiftRegistry\Model\Observer',
        'Magento\GiftRegistry\Observer\AddressFormatAdmin::invoke'
    ],
    [
        'deleteProduct',
        'Magento\GiftRegistry\Model\Observer',
        'Magento\GiftRegistry\Observer\DeleteProduct::invoke'
    ],
    [
        'orderPlaced',
        'Magento\GiftRegistry\Model\Observer',
        'Magento\GiftRegistry\Observer\OrderPlaced::invoke'
    ],
    ['removeIndexByProductIds', 'Magento\TargetRule\Model\Resource\Index'],
    ['saveFlag', 'Magento\TargetRule\Model\Resource\Index']
];
