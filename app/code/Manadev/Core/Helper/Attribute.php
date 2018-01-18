<?php
/**
 * Created by PhpStorm.
 * User: Vernard
 * Date: 8/14/2015
 * Time: 9:56 PM
 */

namespace Manadev\Core\Helper;

use Magento\Framework\App\ResourceConnection;

class Attribute
{
    protected $_attributes = array();
    /**
     * @var Resource|Resource
     */
    private $resource;

    /**
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {

        $this->resource = $resource;
        $this->db = $resource->getConnection('default');
    }

    public function getAttribute($entityType, $attributeCode, $columns) {
        $key = $entityType . '-' . $attributeCode . '-' . implode('-', $columns);
        if (!isset($this->_attributes[$key])) {
            $this->_attributes[$key] = $this->db->fetchRow($this->db->select()
                ->from(array('a' => $this->db->getTableName('eav_attribute')), $columns)
                ->join(array('t' => $this->db->getTableName('eav_entity_type')), 't.entity_type_id = a.entity_type_id', null)
                ->where('a.attribute_code = ?', $attributeCode)
                ->where('t.entity_type_code = ?', $entityType));
        }

        return $this->_attributes[$key];
    }

    public function getAttributeTable($attribute, $baseTable = 'catalog_category_entity') {
        return $attribute['backend_table'] ?
            $attribute['backend_table'] :
            $this->db->getTableName($baseTable . '_' . $attribute['backend_type']);
    }

}