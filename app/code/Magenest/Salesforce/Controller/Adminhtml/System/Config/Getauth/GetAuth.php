<?php
/**
 * * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\Salesforce\Controller\Adminhtml\System\Config\Getauth;

use Magento\Backend\App\Action;
use Magenest\Salesforce\Model\Connector;
use Magenest\Salesforce\Model\Sync\Product;

/**
 * Class GetAuth
 * @package Magenest\Salesforce\Controller\Adminhtml\System\Config\Getauth
 */
class GetAuth extends Action
{
    const ERROR_CONNECT_TO_SALESFORCECRM = 'INVALID_PASSWORD';

    /**
     * @var \Magenest\Salesforce\Model\Connector
     */
    protected $_connector;

    /**
     * @var Product
     */
    protected $_syncProduct;

    /**
     * GetAuth constructor.
     * @param Action\Context $context
     * @param Connector $connector
     * @param Product $syncProduct
     */
    public function __construct(
        Action\Context $context,
        Connector $connector,
        Product $syncProduct
    ) {
        parent::__construct($context);
        $this->_connector   = $connector;
        $this->_syncProduct = $syncProduct;
    }

    /**
     * Check whether vat is valid
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (empty($data['username']) || empty($data['password']) || empty($data['client_id'])|| empty($data['client_secret']) || empty($data['security_token'])) {
                $result['error']       = 1;
                $result['description'] = "Please enter all information";
                echo json_encode($result);
                return;
            }

            $response = $this->_connector->getAccessToken($data, true);
            if (!empty($response['error'])) {
                $result['error']       = 1;
                $result['description'] = $response['error_description'];
                echo json_encode($result);
                return;
            } else {
                $result = $response;
                $result['error'] = 0;
                try {
                    $this->_syncProduct->syncShippingProduct();
                    $this->_syncProduct->syncTaxProduct();
                } catch (\Exception $e) {
                    $result['message'] = $e->getMessage();
                }
                echo json_encode($result);
                return;
            }
        }
    }
}
