<?php
namespace Magenest\Salesforce\Observer\Customer;

use Magenest\Salesforce\Model\Queue;
use Magenest\Salesforce\Model\QueueFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magenest\Salesforce\Model\Sync\Lead;
use Magenest\Salesforce\Model\Sync\Contact;
use Magenest\Salesforce\Model\Sync\Account;

abstract class AbstractCustomer implements ObserverInterface
{
    const XML_SETTING_PATH = 'salesforcecrm/sync/';

    /**
     * @var QueueFactory
     */
    protected $queueFactory;

    protected $scopeConfig;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Lead
     */
    protected $_lead;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Contact
     */
    protected $_contact;

    /**
     * @var \Magenest\Salesforce\Model\Sync\Account
     */
    protected $_account;

    public function __construct(
        QueueFactory $queueFactory,
        ScopeConfigInterface $config,
        Lead $lead,
        Contact $contact,
        Account $account
    ) {
        $this->_lead        = $lead;
        $this->_contact     = $contact;
        $this->_account     = $account;
        $this->queueFactory = $queueFactory;
        $this->scopeConfig = $config;
    }

    public function getEnableConfig($type)
    {
        $path = self::XML_SETTING_PATH . $type;
        return $this->scopeConfig->getValue($path);
    }

    public function getSyncModeConfig($type)
    {
        $path = self::XML_SETTING_PATH . $type . '_mode';
        return $this->scopeConfig->getValue($path);
    }

    /**
     * @param Customer $customer
     */
    public function syncContact($customer)
    {
        if ($this->getEnableConfig('contact')) {
            if ($this->getSyncModeConfig('contact') == 1) {
                /** add to queue mode */
                $this->addToQueue(Queue::TYPE_CONTACT, $customer->getId());
            } else {
                /** auto sync mode */
                $id = $customer->getId();
                $this->_contact->sync($id, true);
            }
        }
    }

    /**
     * @param Customer $customer
     */
    public function syncLead($customer)
    {
        if ($this->getEnableConfig('lead')) {
            if ($this->getSyncModeConfig('lead') == 1) {
                /** add to queue mode */
                $this->addToQueue(Queue::TYPE_LEAD, $customer->getId());
            } else {
                /** auto sync mode */
                $id = $customer->getId();
                $this->_lead->sync($id, true);
            }
        }
    }

    /**
     * @param Customer $customer
     */
    public function syncAccount($customer)
    {
        if ($this->getEnableConfig('account')) {
            if ($this->getSyncModeConfig('account') == 1) {
                /** add to queue mode */
                $this->addToQueue(Queue::TYPE_ACCOUNT, $customer->getId());
            } else {
                /** auto sync mode */
                $id = $customer->getId();
                $this->_account->sync($id, true);
            }
        }
    }

    public function addToQueue($type, $entityId)
    {
        /** add to queue mode */
        $queue = $this->queueFactory->create()
            ->getCollection()
            ->addFieldToFilter('type', $type)
            ->addFieldToFilter('entity_id', $entityId)
            ->getFirstItem();
        if ($queue->getId()) {
            /** Creditmemo existed in queue */
            $queue =  $this->queueFactory->create()->load($queue->getId());
            $queue->setEnqueueTime(time());
            $queue->save();
        }
        $queue = $this->queueFactory->create();
        $data = [
            'type' =>  $type,
            'entity_id' => $entityId,
            'enqueue_time' => time(),
            'priority' => 1,
        ];
        $queue->setData($data);
        $queue->save();
    }
}
