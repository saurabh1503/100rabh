<?php


namespace TNA\Profile\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ICERepositoryInterface
{


    /**
     * Save ICE
     * @param \TNA\Profile\Api\Data\ICEInterface $iCE
     * @return \TNA\Profile\Api\Data\ICEInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function save(\TNA\Profile\Api\Data\ICEInterface $iCE);

    /**
     * Retrieve ICE
     * @param string $iceId
     * @return \TNA\Profile\Api\Data\ICEInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getById($iceId);

    /**
     * Retrieve ICE matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \TNA\Profile\Api\Data\ICESearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ICE
     * @param \TNA\Profile\Api\Data\ICEInterface $iCE
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function delete(\TNA\Profile\Api\Data\ICEInterface $iCE);

    /**
     * Delete ICE by ID
     * @param string $iceId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function deleteById($iceId);
}
