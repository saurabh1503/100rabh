<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Migration\Code\ConfigConverter\AdminhtmlExtractor;

use \Magento\Migration\Code\ConfigConverter\ConfigSectionsInterface;
use \Magento\Migration\Code\ConfigConverter\ConfigSectionsAbstract;

class AdminhtmlAcl extends ConfigSectionsAbstract implements ConfigSectionsInterface
{
    /**
     * @var string
     */
    protected $fileName = 'acl';
    /**
     * @var array
     */
    protected $locations = [
        'acl' => '.'
    ];

    /**
     * @var string[]
     */
    protected $xsls = ['config.xsl'];

    /**
     * @var string
     */
    protected $xmlSchema = 'urn:magento:framework:Acl/etc/acl.xsd';
}
