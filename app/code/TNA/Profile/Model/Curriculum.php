<?php


namespace TNA\Profile\Model;

use TNA\Profile\Api\Data\CurriculumInterface;

class Curriculum extends \Magento\Framework\Model\AbstractModel implements CurriculumInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('TNA\Profile\Model\ResourceModel\Curriculum');
    }

    /**
     * Get archive_id
     * @return string
     */
    public function getCurriculumId()
    {
        return $this->getData(self::CURRICULUM_ID);
    }

    /**
     * Set archive_id
     * @param string $curriculumId
     * @return TNA\Profile\Api\Data\CurriculumInterface
     */
    public function setCurriculumId($curriculumId)
    {
        return $this->setData(self::CURRICULUM_ID, $curriculumId);
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
     * @return TNA\Profile\Api\Data\CurriculumInterface
     */
    public function setDocumentName($document_name)
    {
        return $this->setData(self::DOCUMENT_NAME, $document_name);
    }
}
