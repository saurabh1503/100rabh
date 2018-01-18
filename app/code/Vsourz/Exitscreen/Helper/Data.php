<?php
namespace Vsourz\Exitscreen\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLED = 'exitscreen/general/enabled';
    protected $_scopeConfig;
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\BlockFactory $blockFactory

    ) {
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_blockFactory = $blockFactory;
    }
    /**
     * Check if enabled
     *
     * @return string|null
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Delay time
     *
     * @return string|null
     */
    public function getDelayTime()
    {
        $delay = $this->scopeConfig->getValue('exitscreen/general/cookie_delay',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $delayHours = $delay/1440;
        return $delayHours;
    }
    /**
     * Get Cookie Expire
     *
     * @return string|null
     */
    public function getCookieExpire()
    {
        $Cookie = $this->scopeConfig->getValue('exitscreen/general/cookie_interval',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

       $cookieHours = $Cookie/24;
       return $cookieHours;
    }
    /**
     * Get Block Id
     *
     * @return string|null
     */
    public function getBlockId(){
        $blockId =  $this->scopeConfig->getValue('exitscreen/general/popup_block',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $blockId;
    }
    /**
     * Get Block Title
     *
     * @return string|null
     */
    public function getBlockTitle(){
        $blockId = $this->getBlockId();
        $blockTitle =  "";
        if ($blockId) {
        $storeId = $this->_storeManager->getStore()->getId();
        /** @var \Magento\Cms\Model\Block $block */
        $block = $this->_blockFactory->create();
        $block->setStoreId($storeId)->load($blockId);

        $blockTitle = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getTitle());
        }
        return   $blockTitle;

    }
    /**
     * showPopUp
     *
     * @return string|null
     */
    public function showPopUp(){
            $allowedPages = $this->scopeConfig->getValue('exitscreen/general/pages',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $pageModel = $objectManager->get('Magento\Cms\Model\Page');
            $currCmsPage= $pageModel->getIdentifier();

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $requestInterface = $objectManager->get('Magento\Framework\App\RequestInterface');

            $moduleName     = $requestInterface->getModuleName();
            $pageArr = explode(',',$allowedPages);

            foreach($pageArr as $key => $value){
                  if($currCmsPage == $value || $moduleName == $value){
                      return "Y";
                  }
            }
    }
    /**
     * Get Height
     *
     * @return string|null
     */
    public function getHeight(){
        $height = $this->scopeConfig->getValue('exitscreen/general/popup_height',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($height == "auto"){
            return $height;
        }
        else{
            return $height."px";
        }
    }
    /**
     * Get Width
     *
     * @return string|null
     */
    public function getWidth(){
        $width = $this->scopeConfig->getValue('exitscreen/general/popup_width',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if($width == "auto"){
            return $width;
        }
        else{
            return $width."px";
        }
    }
    /**
     * Get Dissagree BlockId
     *
     * @return string|null
     */
    public function getDissagreeBlockId(){
        $dissagreeblockId = $this->scopeConfig->getValue('exitscreen/general/notverifyblock',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $dissagreeblockId;
    }
    /**
     * Get Agree
     *
     * @return string|null
     */
    public function getAgree(){
        $agree = $this->scopeConfig->getValue('exitscreen/general/agree',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $agree;
    }
    /**
     * Get Disagree
     *
     * @return string|null
     */
    public function getDisagree(){
        $disagree = $this->scopeConfig->getValue('exitscreen/general/disagree',
        \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        return $disagree;
    }
}