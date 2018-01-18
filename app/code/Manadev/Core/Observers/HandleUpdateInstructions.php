<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */
namespace Manadev\Core\Observers;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Layout;

class HandleUpdateInstructions implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $localeDate;
    /**
     * @var Layout
     */
    private $layout;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        Layout $layout
    ) {
        $this->localeDate = $localeDate;
        $this->layout = $layout;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $page = $observer->getData('page');
        $inRange = $this->localeDate->isScopeDateInInterval(
            null,
            $page->getCustomThemeFrom(),
            $page->getCustomThemeTo()
        );
        $layoutUpdate = ($page->getCustomLayoutUpdateXml() && $inRange) ? $page->getCustomLayoutUpdateXml() : $page->getLayoutUpdateXml();

        $layoutUpdate = '<?xml version="1.0"?>
<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">

    '. $layoutUpdate .'

</page>
';
        if($xml = simplexml_load_string($layoutUpdate, 'Magento\Framework\View\Layout\Element')) {
            foreach ($xml->children() as $child) {
                if (strtolower($child->getName()) == 'update' && isset($child['handle'])) {
                    $this->layout->getUpdate()->addHandle((string)$child['handle']);
                }
            }
        }
    }
}