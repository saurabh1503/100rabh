<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Zend_Db_Expr;

class Db {
    /**
     * @param AdapterInterface $db
     * @param $tableName
     * @param array $fields
     * @param bool $onDuplicate
     * @return string
     */
    public function insert($db, $tableName, $fields = [], $onDuplicate = true) {
        $sql = "INSERT INTO `{$tableName}` ";
        $sql .= "(`" . implode('`,`', array_keys($fields)) . "`) ";
        $sql .= "VALUES (" . implode(',', $fields) . ") ";

        if ($onDuplicate && $fields) {
            $sql .= " ON DUPLICATE KEY UPDATE";
            $updateFields = [];
            foreach ($fields as $key => $field) {
                $key = $db->quoteIdentifier($key);
                $updateFields[] = "{$key}=VALUES({$key})";
            }
            $sql .= " " . implode(', ', $updateFields);
        }

        return $sql;
    }

    public function wrapIntoZendDbExpr($fields) {
        $result = array();
        foreach ($fields as $key => $value) {
            $result[$key] = new Zend_Db_Expr($value);
        }
        return $result;
    }
}