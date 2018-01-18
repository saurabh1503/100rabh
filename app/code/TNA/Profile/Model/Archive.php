<?php


namespace TNA\Profile\Model;

use TNA\Profile\Api\Data\ArchiveInterface;

class Archive extends \Magento\Framework\Model\AbstractModel implements ArchiveInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('TNA\Profile\Model\ResourceModel\Archive');
    }

    /**
     * Get archive_id
     * @return string
     */
    public function getArchiveId()
    {
        return $this->getData(self::ARCHIVE_ID);
    }

    /**
     * Set archive_id
     * @param string $archiveId
     * @return TNA\Profile\Api\Data\ArchiveInterface
     */
    public function setArchiveId($archiveId)
    {
        return $this->setData(self::ARCHIVE_ID, $archiveId);
    }

    /**
     * Get document_name
     * @return string
     */
    public function getDocumentName()
    {
        return $this->getData(self::DOCUMENT_NAME);
    }

    /**
     * Set document_name
     * @param string $document_name
     * @return TNA\Profile\Api\Data\ArchiveInterface
     */
    public function setDocumentName($document_name)
    {
        return $this->setData(self::DOCUMENT_NAME, $document_name);
    }
}
