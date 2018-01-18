<?php


namespace TNA\Events\Controller\Adminhtml;

abstract class Continuingeducation extends \Magento\Backend\App\Action
{

    protected $_coreRegistry;
    const ADMIN_RESOURCE = 'TNA_Events::top_level';

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Experius_Test::top_level')
            ->addBreadcrumb(__('TNA'), __('TNA'))
            ->addBreadcrumb(__('Continuing Education'), __('Continuing Education'));
        return $resultPage;
    }
}
