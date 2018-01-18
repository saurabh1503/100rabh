<?php


namespace TNA\Profile\Api\Data;

interface ICESearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get ICE list.
     * @return \TNA\Profile\Api\Data\ICEInterface[]
     */

    public function getItems();

    /**
     * Set participant_name list.
     * @param \TNA\Profile\Api\Data\ICEInterface[] $items
     * @return $this
     */

    public function setItems(array $items);
}
