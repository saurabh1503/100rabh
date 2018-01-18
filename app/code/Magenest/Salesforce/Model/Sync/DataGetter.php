<?php
namespace Magenest\Salesforce\Model\Sync;

class DataGetter
{
    protected $_job;

    public function __construct(
        Job $job
    ) {
        $this->_job = $job;
    }

    /**
     * return an array of contacts on Salesforce
     * @return array|mixed|string
     */
    public function getAllSalesforceContacts()
    {
        $query = 'SELECT id, Email FROM Contact';
        $result = $this->_job->sendBatchRequest('query', 'Contact', $query);
        return $result;
    }

    /**
     * return an array of accounts on Salesforce
     * @return array|mixed|string
     */
    public function getAllSalesforceAccounts()
    {
        $query = 'SELECT id, Name FROM Account';
        $result = $this->_job->sendBatchRequest('query', 'Account', $query);
        return $result;
    }

    /**
     * return an array of leads on Salesforce
     * @return array|mixed|string
     */
    public function getAllSalesforceLeads()
    {
        $query = 'SELECT id, Email FROM Lead';
        $result = $this->_job->sendBatchRequest('query', 'Lead', $query);
        return $result;
    }

    /**
     * return an array of products on Salesforce
     * @return mixed|string
     */
    public function getAllSalesforceProducts()
    {
        $query = 'SELECT id, ProductCode FROM Product2';
        $result = $this->_job->sendBatchRequest('query', 'Product2', $query);
        return $result;
    }

    /**
     * return an array of pricebook entries on Salesforce
     * @return mixed|string
     */
    public function getAllPricebookEntry()
    {
        $query = "SELECT Id, Product2Id, ProductCode FROM PricebookEntry ORDER BY Id";
        $result = $this->_job->sendBatchRequest('query', 'PricebookEntry', $query);
        return $result;
    }

    /**
     * return an array of pricebook entries on Salesforce
     * @return mixed|string
     */
    public function getAllSalesforceOrders()
    {
        $query = "SELECT Id, OrderNumber FROM Order";
        $result = $this->_job->sendBatchRequest('query', 'Order', $query);
        return $result;
    }

    /**
     * return an array of Campaigns on Salesforce
     * @return mixed|string
     */
    public function getAllSalesforceCampaigns()
    {
        $query = "SELECT Id,Name FROM Campaign";
        $result = $this->_job->sendBatchRequest('query', 'Campaign', $query);
        return $result;
    }
}
