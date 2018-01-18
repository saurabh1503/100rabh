<?php
namespace Efloor\Requestquote\Block\Adminhtml\Grid\Quoteview;

//use Efloor\Requestquote\Api\Data\GridInterface;
//use Efloor\Requestquote\Model\ResourceModel\Grid\Collection as PostCollection;

class Quoteview extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var \\Efloor\Review\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_postCollectionFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context 
     * @param \Efloor\Review\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Efloor\Requestquote\Model\Grid $post,
        \Efloor\Requestquote\Model\PostFactory $postFactory,
        array $data = []
    )
    {
        
        die('hello');
        parent::__construct($context, $data);
        $this->_post = $post;
        $this->_postFactory = $postFactory;
    }
    /**
     * @return \Ashsmith\Blog\Model\Post
     */
    public function getPost()
    {
        // Check if posts has already been defined
        // makes our block nice and re-usable! We could
        // pass the 'posts' data to this block, with a collection
        // that has been filtered differently!
        if (!$this->hasData('post')) {
            if ($this->getPostId()) {
                /** @var \Ashsmith\Blog\Model\Post $page */
                $post = $this->_postFactory->create();
            } else {
                $post = $this->_post;
            }
            $this->setData('post', $post);
        }
        return $this->getData('post');
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Efloor\Requestquote\Model\Grid::CACHE_TAG . '_' . 'list'];
    }
}
?>