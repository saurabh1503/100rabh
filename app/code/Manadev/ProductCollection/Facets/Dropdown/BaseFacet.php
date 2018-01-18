<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\ProductCollection\Facets\Dropdown;

use Manadev\ProductCollection\Contracts\Facet;

abstract class BaseFacet extends Facet
{
    /**
     * @var
     */
    protected $attributeId;

    /**
     * @var
     */
    protected $selectedOptionIds;
    /**
     * @var
     */
    protected $hideWithSingleVisibleItem;

    public function __construct($name, $attributeId, $selectedOptionIds, $hideWithSingleVisibleItem) {
        parent::__construct($name);
        $this->attributeId = $attributeId;
        $this->selectedOptionIds = $selectedOptionIds;
        $this->hideWithSingleVisibleItem = $hideWithSingleVisibleItem;
    }

    /**
     * @return mixed
     */
    public function getAttributeId() {
        return $this->attributeId;
    }

    /**
     * @return mixed
     */
    public function getSelectedOptionIds() {
        return $this->selectedOptionIds;
    }

    public function getHideWithSingleVisibleItem() {
        return $this->hideWithSingleVisibleItem;
    }
}
