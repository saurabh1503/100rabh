<?php


namespace TNA\Events\Api\Data;

interface ContinuingEducationInterface
{

    const STATE_CODE = 'state_code';
    const CREDIT_HOURS = 'credit_hours';
    const LICENSE_LABEL = 'license_label';
    const LICENSE_TYPE = 'license_type';
    const EVENT_CODE = 'event_code';
    const CONTINUING_EDUCATION_ID = 'continuing_education_id';
    const GOOD_STANDING = 'good_standing';


    /**
     * Get continuing_education_id
     * @return string|null
     */

    public function getContinuingEducationId();

    /**
     * Set continuing_education_id
     * @param string $continuing_education_id
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */

    public function setContinuingEducationId($continuingEducationId);

    /**
     * Get event_code
     * @return string|null
     */

    public function getEventCode();

    /**
     * Set event_code
     * @param string $event_code
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */

    public function setEventCode($event_code);

    /**
     * Get state_code
     * @return string|null
     */

    public function getStateCode();

    /**
     * Set state_code
     * @param string $state_code
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */

    public function setStateCode($state_code);

    /**
     * Get license_type
     * @return string|null
     */

    public function getLicenseType();

    /**
     * Set license_type
     * @param string $license_type
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */

    public function setLicenseType($license_type);

    /**
     * Get license_label
     * @return string|null
     */

    public function getLicenseLabel();

    /**
     * Set license_label
     * @param string $license_label
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */

    public function setLicenseLabel($license_label);

    /**
     * Get credit_hours
     * @return string|null
     */

    public function getCreditHours();

    /**
     * Set credit_hours
     * @param string $credit_hours
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */

    public function setCreditHours($credit_hours);

    /**
     * Get good_standing
     * @return string|null
     */

    public function getGoodStanding();

    /**
     * Set good_standing
     * @param string $good_standing
     * @return TNA\Events\Api\Data\ContinuingEducationInterface
     */

    public function setGoodStanding($good_standing);
}
