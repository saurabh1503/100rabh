<?php


namespace TNA\Profile\Api\Data;

interface ICEInterface
{

    const EVENT_ATTENDING = 'event_attending';
    const ICE_ID = 'ice_id';
    const PARTICIPANT_NAME = 'participant_name';


    /**
     * Get ice_id
     * @return string|null
     */

    public function getIceId();

    /**
     * Set ice_id
     * @param string $ice_id
     * @return TNA\Profile\Api\Data\ICEInterface
     */

    public function setIceId($iceId);

    /**
     * Get participant_name
     * @return string|null
     */

    public function getParticipantName();

    /**
     * Set participant_name
     * @param string $participant_name
     * @return TNA\Profile\Api\Data\ICEInterface
     */

    public function setParticipantName($participant_name);

    /**
     * Get event_attending
     * @return string|null
     */

    public function getEventAttending();

    /**
     * Set event_attending
     * @param string $event_attending
     * @return TNA\Profile\Api\Data\ICEInterface
     */

    public function setEventAttending($event_attending);
}
