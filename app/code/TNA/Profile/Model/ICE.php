<?php


namespace TNA\Profile\Model;

use TNA\Profile\Api\Data\ICEInterface;

class ICE extends \Magento\Framework\Model\AbstractModel implements ICEInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('TNA\Profile\Model\ResourceModel\ICE');
    }

    /**
     * Get ice_id
     * @return string
     */
    public function getIceId()
    {
        return $this->getData(self::ICE_ID);
    }

    /**
     * Set ice_id
     * @param string $iceId
     * @return TNA\Profile\Api\Data\ICEInterface
     */
    public function setIceId($iceId)
    {
        return $this->setData(self::ICE_ID, $iceId);
    }

    /**
     * Get participant_name
     * @return string
     */
    public function getParticipantName()
    {
        return $this->getData(self::PARTICIPANT_NAME);
    }

    /**
     * Set participant_name
     * @param string $participant_name
     * @return TNA\Profile\Api\Data\ICEInterface
     */
    public function setParticipantName($participant_name)
    {
        return $this->setData(self::PARTICIPANT_NAME, $participant_name);
    }

    /**
     * Get event_attending
     * @return string
     */
    public function getEventAttending()
    {
        return $this->getData(self::EVENT_ATTENDING);
    }

    /**
     * Set event_attending
     * @param string $event_attending
     * @return TNA\Profile\Api\Data\ICEInterface
     */
    public function setEventAttending($event_attending)
    {
        return $this->setData(self::EVENT_ATTENDING, $event_attending);
    }
}
