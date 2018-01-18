<?php


namespace TNA\Profile\Block\Archive;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->customerAccountManagement = $customerAccountManagement;
        parent::__construct($context, $data);
    }
    /*
     * Return the Customer given the customer Id stored in the session.
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customerRepository->getById($this->customerSession->getCustomerId());
    }

    /**
     * Returns the Magento Customer Model for this block
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface|null
     */
    public function getDocuments()
    {
        try {
            return $this->getCustomer();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
