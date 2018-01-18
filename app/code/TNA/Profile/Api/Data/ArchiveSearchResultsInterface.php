<?php


namespace TNA\Profile\Api\Data;

interface ArchiveSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Archive list.
     * @return \TNA\Profile\Api\Data\ArchiveInterface[]
     */

    public function getItems();

    /**
     * Set document_name list.
     * @param \TNA\Profile\Api\Data\ArchiveInterface[] $items
     * @return $this
     */

    public function setItems(array $items);
}
