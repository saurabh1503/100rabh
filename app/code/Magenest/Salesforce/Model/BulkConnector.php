<?php
namespace Magenest\Salesforce\Model;

use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config as ResourceModelConfig;

class BulkConnector
{
    /**
     *#@+
     * Constants
     */
    const XML_PATH_SALESFORCE_EMAIL          = 'salesforcecrm/config/email';
    const XML_PATH_SALESFORCE_PASSWD         = 'salesforcecrm/config/passwd';
    const XML_PATH_SALESFORCE_SECURITY_TOKEN = 'salesforcecrm/config/security_token';
    const XML_PATH_SALESFORCE_INSTANCE_URL   = 'salesforcecrm/config/instance_url';

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var RequestLogFactory
     */
    protected $requestLogFactory;

    /**
     * BulkConnector constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param RequestLogFactory $requestLogFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        RequestLogFactory $requestLogFactory
    ) {
        $this->_scopeConfig    = $scopeConfig;
        $this->requestLogFactory = $requestLogFactory;
    }

    /**
     * @return string
     */
    public function _getAccessToken()
    {
        try {
            $xml = '<?xml version="1.0" encoding="utf-8" ?>';
            $xml .= '<env:Envelope xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:env="http://schemas.xmlsoap.org/soap/envelope/">';
            $xml .= '<env:Body>';
            $xml .= '<n1:login xmlns:n1="urn:partner.soap.sforce.com">';
            $xml .= '<n1:username>' . $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_EMAIL) . '</n1:username>';
            $xml .= '<n1:password>' . $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_PASSWD) . $this->_scopeConfig->getValue(self::XML_PATH_SALESFORCE_SECURITY_TOKEN) . '</n1:password>';
            $xml .= '</n1:login>';
            $xml .= '</env:Body>';
            $xml .= '</env:Envelope>';
            $xml = trim($xml);
            $url = 'https://login.salesforce.com/services/Soap/u/38.0';
            $headers = [
                'Content-Type' => 'text/xml',
                'charset' => 'UTF-8',
                'SOAPAction' => 'login'
            ];
            $response = $this->sendRequest($url, \Zend_Http_Client::POST, $headers, $xml);
            $parsedResponse = $this->parseXml($response);
            if (!$this->sessionId = $parsedResponse->getElementsByTagName('sessionId')[0]->nodeValue) {
                throw new \Exception('Cant get access token');
            }
        } catch (\Exception $e) {
            echo 'Exception Message: ' . $e->getMessage() . '<br/>';
            return $e->getMessage();
        }
    }

    protected function sendRequest($url, $method, $headers = [], $params = '')
    {
        $client = new \Zend_Http_Client($url);
        $client->setHeaders($headers);
        $client->setMethod($method);
        if ($method != \Zend_Http_Client::GET) {
            if (isset($headers['Content-Type'])) {
                $client->setRawData($params);
                $client->setEncType($headers['Content-Type']);
            } else {
                $client->setParameterPost($params);
            }
        }
        $response = $client->request()->getBody();
        $this->requestLogFactory->create()->addRequest(RequestLog::BULK_REQUEST_TYPE);
        return $response;
    }

    protected function parseXml($xml)
    {
        try {
            $parser = new \Magento\Framework\Xml\Parser();
            $parser->loadXML($xml);
            return $parser->getDom();
        } catch (\Exception $e) {
            \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($e->getMessage());
        }
        return false;
    }

    public function getAccessToken()
    {
        if (!$this->sessionId) {
            $this->_getAccessToken();
        }
        return $this->sessionId;
    }
}
