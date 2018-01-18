<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Plugins;

use Magento\Framework\View\Page;
use Magento\Framework\View\Result\Layout as LayoutResultView;
use Magento\Framework\App\ResponseInterface;
use Manadev\LayeredNavigation\Engine;

class ResultLayoutView
{
    /**
     * @var Page\Config
     */
    protected $pageConfig;
    /**
     * @var Engine
     */
    protected $engine;

    public function __construct(Page\Config $pageConfig, Engine $engine) {
        $this->pageConfig = $pageConfig;
        $this->engine = $engine;
    }

    public function beforeRenderResult(LayoutResultView $view, ResponseInterface $response) {
        if ($this->isAnyFilterHasMoreThanSpecifiedNumberOfAppliedOptions(1)) {
            $this->pageConfig->setRobots('NOINDEX,NOFOLLOW');
        }
    }

    protected function isAnyFilterHasMoreThanSpecifiedNumberOfAppliedOptions($maxAppliedOptions) {
        foreach ($this->engine->getFilters() as $engineFilter) {
            $appliedOptions = $engineFilter->getAppliedOptions();
            if ($appliedOptions === false || count($appliedOptions) <= $maxAppliedOptions) {
                continue;
            }

            return true;
        }

        return false;
    }
}