<?php


namespace TNA\Profile\Api\Data;

interface CurriculumInterface
{

    const CURRICULUM_ID = 'curriculum_id';
    const DOCUMENT_NAME = 'document_name';


    /**
     * Get archive_id
     * @return string|null
     */

    public function getCurriculumId();

    /**
     * Set archive_id
     * @param string $archive_id
     * @return TNA\Profile\Api\Data\ArchiveInterface
     */

    public function setCurriculumId($curriculumId);

    /**
     * Get document_name
     * @return string|null
     */

    public function getDocumentName();

    /**
     * Set document_name
     * @param string $document_name
     * @return TNA\Profile\Api\Data\ArchiveInterface
     */

    public function setDocumentName($document_name);
}
