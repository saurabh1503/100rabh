<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation\Plugins;

use Closure;
use Magento\Config\Model\Config;
use Manadev\LayeredNavigation\Registries\FilterIndexers\PrimaryFilterIndexers;
use Manadev\LayeredNavigation\Registries\FilterIndexers\SecondaryFilterIndexers;
use Manadev\LayeredNavigation\Resources\Indexers\FilterIndexer;

class ConfigModel
{
    /**
     * @var Config\Loader
     */
    protected $_configLoader;
    /**
     * @var PrimaryFilterIndexers
     */
    protected $primaryFilterIndexers;
    /**
     * @var SecondaryFilterIndexers
     */
    protected $secondaryFilterIndexers;
    protected $usedStoreConfigPaths;
    /**
     * @var FilterIndexer
     */
    protected $indexer;

    public function __construct(Config\Loader $configLoader, PrimaryFilterIndexers $primaryFilterIndexers,
        SecondaryFilterIndexers $secondaryFilterIndexers, FilterIndexer $indexer)
    {
        $this->_configLoader = $configLoader;
        $this->primaryFilterIndexers = $primaryFilterIndexers;
        $this->secondaryFilterIndexers = $secondaryFilterIndexers;
        $this->indexer = $indexer;
    }

    public function aroundSave(Config $configModel, Closure $proceed) {
        $data = $configModel->getData();
        $oldConfig = $this->_getConfig($configModel, true);

        $returnValue = $proceed();

        $reindexRequired = false;
        foreach ($data['groups'] as $groupId => $group) {
            if (!isset($group['fields'])) {
                continue;
            }

            foreach ($group['fields'] as $fieldId => $field) {
                $path = "{$data['section']}/{$groupId}/{$fieldId}";

                if (isset($oldConfig[$path]) && $oldConfig[$path]['value'] == $field['value']) {
                    continue;
                }

                if (!isset($this->getUsedStoreConfigPaths()[$path])) {
                    continue;
                }

                $reindexRequired = true;
                break;
            }
            if($reindexRequired) {
                break;
            }
        }
        if ($reindexRequired) {
            $this->indexer->reindexAll();
        }

        return $returnValue;
    }

    protected function _getConfig($configModel, $full = true)
    {
        return $this->_configLoader->getConfigByPath(
            $configModel->getSection(),
            $configModel->getScope(),
            $configModel->getScopeId(),
            $full
        );
    }

    protected function getUsedStoreConfigPaths() {
        if (!$this->usedStoreConfigPaths) {
            $this->usedStoreConfigPaths = [];

            foreach ($this->primaryFilterIndexers->getList() as $indexer) {
                $this->usedStoreConfigPaths = array_merge($this->usedStoreConfigPaths, $indexer->getUsedStoreConfigPaths());
            }
            foreach ($this->secondaryFilterIndexers->getList() as $indexer) {
                $this->usedStoreConfigPaths = array_merge($this->usedStoreConfigPaths, $indexer->getUsedStoreConfigPaths());
            }

            $this->usedStoreConfigPaths = array_flip($this->usedStoreConfigPaths);
        }

        return $this->usedStoreConfigPaths;
    }
}