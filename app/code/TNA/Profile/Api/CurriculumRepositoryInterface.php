<?php


namespace TNA\Profile\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CurriculumRepositoryInterface
{


    /**
     * Save Archive
     * @param \TNA\Profile\Api\Data\ArchiveInterface $archive
     * @return \TNA\Profile\Api\Data\ArchiveInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function save(
        \TNA\Profile\Api\Data\CurriculumInterface $curriculum
    );

    /**
     * Retrieve Archive
     * @param string $archiveId
     * @return \TNA\Profile\Api\Data\ArchiveInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getById($curriculumId);

    /**
     * Retrieve Archive matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \TNA\Profile\Api\Data\ArchiveSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Archive
     * @param \TNA\Profile\Api\Data\ArchiveInterface $archive
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function delete(
        \TNA\Profile\Api\Data\CurriculumInterface $curriculum
    );

    /**
     * Delete Archive by ID
     * @param string $archiveId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function deleteById($curriculumId);
}
