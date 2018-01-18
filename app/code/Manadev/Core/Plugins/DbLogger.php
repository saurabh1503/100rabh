<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core\Plugins;

use Magento\Framework\DB\LoggerInterface;
use Manadev\Core\QueryLogger;

class DbLogger {
    /**
     * @var QueryLogger
     */
    private $queryLogger;

    public function __construct(QueryLogger $queryLogger) {
        $this->queryLogger = $queryLogger;
    }

    public function beforeLogStats(LoggerInterface $dbLogger, $type, $sql, $bind = [], $result = null) {
        $this->queryLogger->log($type, $sql, $bind, $result);
    }
}