<?php
namespace Magenest\Salesforce\Model\Sync;

use Magenest\Salesforce\Model\RequestLogFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magenest\Salesforce\Model\BulkConnector;

class Job extends BulkConnector
{
    /**
     * @var string
     */
    protected $jobId;

    /**
     * @var string
     */
    protected $batchId;
    
    public function __construct(ScopeConfigInterface $scopeConfig, RequestLogFactory $requestLogFactory)
    {
        parent::__construct($scopeConfig, $requestLogFactory);
    }

    /**
     * @param string $operation
     * @param string $object
     * @param string $batch
     * @param string $contentType
     * @return mixed|string
     */
    public function sendBatchRequest($operation = '', $object = '', $batch = '', $contentType = 'JSON')
    {
        try {
            if ($batch == '[]') {
                return 'Batch is empty';
            }
            $batchResultId = '';
            $this->getAccessToken();
            $this->createJob($operation, $object, $contentType);
            $this->addBatch($batch);
            if ($operation == 'query') {
                $batchResultId = $this->getBatchResultId();
            }
            $queryResult = $this->getBatchResult($batchResultId);
            $this->closeJob();
            return $queryResult;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }

    protected function createJob($operation = '', $object = '', $contentType = 'JSON')
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<jobInfo xmlns="http://www.force.com/2009/06/asyncapi/dataload">';
        $xml .= '<operation>'.$operation.'</operation>';
        $xml .= '<object>'.$object.'</object>';
        $xml .= '<contentType>'.$contentType.'</contentType>';
        $xml .= '</jobInfo>';
        $xml = trim($xml);
        $url = $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_INSTANCE_URL).'/services/async/38.0/job';
        $headers = [
            'Content-Type' => 'text/xml',
            'charset' => 'UTF-8',
            'X-SFDC-Session' => $this->sessionId
        ];
        $response = $this->sendRequest($url, \Zend_Http_Client::POST, $headers, $xml);
        $parsedReponse = $this->parseXml($response);
        if (!$this->jobId = $parsedReponse->getElementsByTagName('id')[0]->nodeValue) {
            throw new \Exception('Cant create Job: '.$response);
        }
    }

    protected function addBatch($batch = '')
    {
        $url = $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_INSTANCE_URL).'/services/async/38.0/job/'.$this->jobId.'/batch/';
        $headers = [
            'Content-Type' => 'application/json',
            'charset' => 'UTF-8',
            'X-SFDC-Session' => $this->sessionId
        ];
        $response = $this->sendRequest($url, \Zend_Http_Client::POST, $headers, $batch);
        $parsedResponse = json_decode($response, true);
        if (!$this->batchId = $parsedResponse['id']) {
            throw new \Exception('Cant add batch to Job: '.$response);
        }
    }

    protected function closeJob()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<jobInfo xmlns="http://www.force.com/2009/06/asyncapi/dataload">';
        $xml .= '<state>Closed</state>';
        $xml .= '</jobInfo>';
        $xml = trim($xml);
        $url = $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_INSTANCE_URL).'/services/async/38.0/job/'.$this->jobId;
        $headers = [
            'Content-Type' => 'text/xml',
            'charset' => 'UTF-8',
            'X-SFDC-Session' => $this->sessionId
        ];
        $this->sendRequest($url, \Zend_Http_Client::POST, $headers, $xml);
    }

    protected function getBatchResultId()
    {
        $url = $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_INSTANCE_URL).'/services/async/38.0/job/'.$this->jobId.'/batch/'.$this->batchId.'/result/';
        $headers = [
            'Content-Type' => 'application/json',
            'charset' => 'UTF-8',
            'X-SFDC-Session' => $this->sessionId
        ];
        $response = $this->sendRequest($url, \Zend_Http_Client::GET, $headers);
        $parsedResponse = json_decode($response, true);
        if (isset($parsedResponse[0]['id'])) {
            return $parsedResponse[0]['id'];
        } elseif (isset($parsedResponse[0])) {
            return $parsedResponse[0];
        } else {
            $response = $this->getBatchStatus();
            throw new \Exception('Cant get Batch Result Id:  '.$response);
        }
    }

    protected function getBatchStatus()
    {
        $url = $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_INSTANCE_URL).'/services/async/38.0/job/'.$this->jobId.'/batch/'.$this->batchId;
        $headers = [
            'Content-Type' => 'application/json',
            'charset' => 'UTF-8',
            'X-SFDC-Session' => $this->sessionId
        ];
        $response = $this->sendRequest($url, \Zend_Http_Client::GET, $headers);
        return $response;
    }


    protected function getBatchResult($resultId = '')
    {
        do {
            $url = $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_INSTANCE_URL) . '/services/async/38.0/job/' . $this->jobId . '/batch/' . $this->batchId . '/result/' . $resultId;
            $headers = [
                'Content-Type' => 'application/json',
                'charset' => 'UTF-8',
                'X-SFDC-Session' => $this->sessionId
            ];
            $response = $this->sendRequest($url, \Zend_Http_Client::GET, $headers);
            $decodedResponse = json_decode($response, true);
        } while (isset($decodedResponse['exceptionMessage']) && $decodedResponse['exceptionMessage'] == 'Batch not completed');
        return $decodedResponse;
    }
}
