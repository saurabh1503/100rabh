<?php


namespace TNA\Profile\Api\Data;

interface ArchiveInterface
{

    const ARCHIVE_ID = 'archive_id';
    const DOCUMENT_NAME = 'document_name';


    /**
     * Get archive_id
     * @return string|null
     */

    public function getArchiveId();

    /**
     * Set archive_id
     * @param string $archive_id
     * @return TNA\Profile\Api\Data\ArchiveInterface
     */

    public function setArchiveId($archiveId);

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
