<?php


namespace TNA\Events\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ContinuingEducationRepositoryInterface
{


    /**
     * Save continuing_education
     * @param \TNA\Events\Api\Data\ContinuingEducationInterface $continuingEducation
     * @return \TNA\Events\Api\Data\ContinuingEducationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function save(
        \TNA\Events\Api\Data\ContinuingEducationInterface $continuingEducation
    );

    /**
     * Retrieve continuing_education
     * @param string $continuingEducationId
     * @return \TNA\Events\Api\Data\ContinuingEducationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getById($continuingEducationId);

    /**
     * Retrieve continuing_education matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \TNA\Events\Api\Data\ContinuingEducationSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete continuing_education
     * @param \TNA\Events\Api\Data\ContinuingEducationInterface $continuingEducation
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function delete(
        \TNA\Events\Api\Data\ContinuingEducationInterface $continuingEducation
    );

    /**
     * Delete continuing_education by ID
     * @param string $continuingEducationId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function deleteById($continuingEducationId);
}
