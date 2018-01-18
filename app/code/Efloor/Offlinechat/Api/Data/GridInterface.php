<?php
namespace Efloor\Requestquote\Api\Data;


interface GridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const POST_ID       = 'id';
    const URL_KEY       = 'url_key';
    const TITLE         = 'email';
    const CONTENT       = 'content';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get URL Key
     *
     * @return string
     */
    public function getUrlKey();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setId($id);

    /**
     * Set URL Key
     *
     * @param string $url_key
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setUrlKey($url_key);


    /**
     * Set title
     *
     * @param string $title
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setTitle($title);

    /**
     * Set content
     *
     * @param string $content
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setContent($content);

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setIsActive($isActive);
}
