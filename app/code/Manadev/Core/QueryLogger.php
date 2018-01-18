<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core;

class QueryLogger {
    protected $files= [];

    /**
     * @var
     */
    private $logger;

    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    public function begin($file) {
        if (isset($this->files[$file])) {
            $this->files[$file]++;
        }
        else {
            $this->files[$file] = 1;
        }
    }

    public function end($file) {
        if (isset($this->files[$file])) {
            if ($this->files[$file] > 1) {
                $this->files[$file]--;
            }
            else {
                unset($this->files[$file]);
            }
        }
    }

    public function log($type, $sql, $bind = [], $result = null) {
        foreach (array_keys($this->files) as $file) {
            $this->logger->debug($sql, ['file' => $file]);
        }
    }
}