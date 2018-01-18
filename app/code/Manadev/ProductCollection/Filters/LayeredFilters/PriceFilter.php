<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Filters\LayeredFilters;

use Manadev\ProductCollection\Enums\Operation;
use Manadev\ProductCollection\Contracts\Filter;

class PriceFilter extends Filter
{
    /**
     * @var
     */
    protected $ranges;
    /**
     * @var string
     */
    protected $operation;
    /**
     * @var
     */
    protected $attributeId;

    public function __construct($name, $attributeId, $ranges, $operation = Operation::LOGICAL_OR) {
        parent::__construct($name);
        $this->ranges = $ranges;
        $this->operation = $operation;
        $this->attributeId = $attributeId;
    }

    public function getType() {
        return 'layered_price';
    }

    public function getAttributeId() {
        return $this->attributeId;
    }

    /**
     * @return mixed
     */
    public function getRanges() {
        return $this->ranges;
    }

    /**
     * @return string
     */
    public function getOperation() {
        return $this->operation;
    }


}