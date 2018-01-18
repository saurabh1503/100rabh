<?php namespace Efloor\Requestquote\Model;

use Efloor\Requestquote\Api\Data\GridInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Grid  extends \Magento\Framework\Model\AbstractModel implements GridInterface, IdentityInterface
{

    /**#@+
     * Post's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'requestquote_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'requestquote_grid';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'requestquote_grid';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Efloor\Requestquote\Model\Resource\Grid');
    }

    /**
     * Check if post url key exists
     * return post id if post exists
     *
     * @param string $url_key
     * @return int
     */
    public function checkUrlKey($url_key)
    {
        return $this->_getResource()->checkUrlKey($url_key);
    }

    /**
     * Prepare post's statuses.
     * Available event blog_post_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::POST_ID);
    }

    /**
     * Get URL Key
     *
     * @return string
     */
    public function getUrlKey()
    {
        return $this->getData(self::URL_KEY);
    }

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Get content
     *
     * @return string|null
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setId($id)
    {
        return $this->setData(self::POST_ID, $id);
    }

    /**
     * Set URL Key
     *
     * @param string $url_key
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setUrlKey($url_key)
    {
        return $this->setData(self::URL_KEY, $url_key);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set content
     *
     * @param string $content
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * Set creation time
     *
     * @param string $creation_time
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setCreationTime($creation_time)
    {
        return $this->setData(self::CREATION_TIME, $creation_time);
    }

    /**
     * Set update time
     *
     * @param string $update_time
     * @return Efloor\Review\Api\Data\PostInterface
     */
    public function setUpdateTime($update_time)
    {
        return $this->setData(self::UPDATE_TIME, $update_time);
    }

    /**
     * Set is active
     *
     * @param int|bool $is_active
     * @return \Efloor\Review\Api\Data\PostInterface
     */
    public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }

}



