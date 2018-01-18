<?php


namespace TNA\Events\Model;

use TNA\Events\Api\Data\ContinuingEducationInterface;

class ContinuingEducation extends \Magento\Framework\Model\AbstractModel implements ContinuingEducationInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('TNA\Events\Model\ResourceModel\ContinuingEducation');
    }

    /**
     * Get continuing_education_id
     * @return string
     */
    public function getContinuingEducationId()
    {
        return $this->getData(self::CONTINUING_EDUCATION_ID);
    }

    /**
     * Set continuing_education_id
     * @param string $continuingEducationId
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */
    public function setContinuingEducationId($continuingEducationId)
    {
        return $this->setData(self::CONTINUING_EDUCATION_ID, $continuingEducationId);
    }

    /**
     * Get event_code
     * @return string
     */
    public function getEventCode()
    {
        return $this->getData(self::EVENT_CODE);
    }

    /**
     * Set event_code
     * @param string $event_code
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */
    public function setEventCode($event_code)
    {
        return $this->setData(self::EVENT_CODE, $event_code);
    }

    /**
     * Get state_code
     * @return string
     */
    public function getStateCode()
    {
        return $this->getData(self::STATE_CODE);
    }

    /**
     * Set state_code
     * @param string $state_code
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */
    public function setStateCode($state_code)
    {
        return $this->setData(self::STATE_CODE, $state_code);
    }

    /**
     * Get license_type
     * @return string
     */
    public function getLicenseType()
    {
        return $this->getData(self::LICENSE_TYPE);
    }

    /**
     * Set license_type
     * @param string $license_type
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */
    public function setLicenseType($license_type)
    {
        return $this->setData(self::LICENSE_TYPE, $license_type);
    }

    /**
     * Get license_label
     * @return string
     */
    public function getLicenseLabel()
    {
        return $this->getData(self::LICENSE_LABEL);
    }

    /**
     * Set license_label
     * @param string $license_label
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */
    public function setLicenseLabel($license_label)
    {
        return $this->setData(self::LICENSE_LABEL, $license_label);
    }

    /**
     * Get credit_hours
     * @return string
     */
    public function getCreditHours()
    {
        return $this->getData(self::CREDIT_HOURS);
    }

    /**
     * Set credit_hours
     * @param string $credit_hours
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */
    public function setCreditHours($credit_hours)
    {
        return $this->setData(self::CREDIT_HOURS, $credit_hours);
    }

    /**
     * Get good_standing
     * @return string
     */
    public function getGoodStanding()
    {
        return $this->getData(self::GOOD_STANDING);
    }

    /**
     * Set good_standing
     * @param string $good_standing
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */
    public function setGoodStanding($good_standing)
    {
        return $this->setData(self::GOOD_STANDING, $good_standing);
    }
}
