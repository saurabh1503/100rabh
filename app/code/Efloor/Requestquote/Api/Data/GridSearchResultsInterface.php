<?php
namespace Efloor\Requestquote\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms page search results.
 * @api
 */
interface GridSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \Efloor\Review\Api\Data\PostInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \Efloor\Review\Api\Data\PostInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
