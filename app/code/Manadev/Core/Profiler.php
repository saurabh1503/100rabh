<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core;

use Magento\Framework\Profiler as MagentoProfiler;

class Profiler
{
    protected $stats = [];
    /**
     * @var Configuration
     */
    protected $configuration;

    public function __construct(Configuration $configuration) {
        $this->configuration = $configuration;
    }

    public function isEnabled() {
        return $this->configuration->isProfilerEnabled();
    }

    public function start($timerName, array $tags = null) {
        if (!$this->isEnabled()) {
            return;
        }

        if (!isset($this->stats[$timerName])) {
            $this->stats[$timerName] = [
                'count' => 0,
                'elapsed_total' => 0,
                'elapsed_average' => 0,
                'elapsed_series' => []
            ];
        }

        $this->stats[$timerName]['started_at'] = microtime(true);
    }

    public function stop($timerName = null) {
        if (!$this->isEnabled($timerName)) {
            return;
        }

        $elapsed = (microtime(true) - $this->stats[$timerName]['started_at']) * 1000;
        unset($this->stats[$timerName]['started_at']);

        $this->stats[$timerName]['elapsed_series'][] = $elapsed;
        $this->stats[$timerName]['elapsed_total'] += $elapsed;
        $this->stats[$timerName]['count'] += 1;
        $this->stats[$timerName]['elapsed_average'] =
            $this->stats[$timerName]['elapsed_total'] / $this->stats[$timerName]['count'];
    }

    public function getStats() {
        return $this->stats;
    }
}