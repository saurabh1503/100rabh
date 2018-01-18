<?php


namespace TNA\Events\Api\Data;

interface ContinuingEducationSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get continuing_education list.
     * @return \TNA\Events\Api\Data\ContinuingEducationInterface[]
     */

    public function getItems();

    /**
     * Set event_code list.
     * @param \TNA\Events\Api\Data\ContinuingEducationInterface[] $items
     * @return $this
     */

    public function setItems(array $items);
}
