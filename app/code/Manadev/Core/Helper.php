<?php
/** 
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\Core;

use Magento\Framework\App\RequestInterface;
use Manadev\Core\Contracts\PageType;
use Manadev\Core\Registries\PageTypes;

class Helper {
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;
    /**
     * @var PageTypes
     */
    protected $pageTypes;

    public function __construct(
        RequestInterface $request,
        PageTypes $pageTypes
    ) {
        $this->request = $request;
        $this->pageTypes = $pageTypes;
    }
    public function getCurrentRoute() {
        return strtolower($this->request->getFullActionName());
    }

    /**
     * @return PageType
     */
    public function getPageType() {
        return $this->pageTypes->get($this->getCurrentRoute());
    }

    public function decodeGridSerializedInput($encoded) {
        $result = array();
        parse_str($encoded, $decoded);
        foreach ($decoded as $key => $value) {
            $result[$key] = null;
            parse_str(base64_decode($value), $result[$key]);
        }

        return $result;
    }

    public function merge($a, $b) {
        if (is_object($a)) {
            if (!is_object($b)) {
                return $a;
            }
            foreach ($b as $key => $value) {
                if (isset($a->$key)) {
                    $a->$key = $this->merge($a->$key, $value);
                }
                else {
                    $a->$key = $value;
                }
            }

            return $a;
        }
        elseif (is_array($a)) {
            if (!is_array($b)) {
                return $a;
            }
            foreach ($b as $key => $value) {
                if (is_numeric($key)) {
                    $a[$key] = $value;
                }
                if (isset($a[$key])) {
                    $a[$key] = $this->merge($a[$key], $value);
                }
                else {
                    $a[$key] = $value;
                }
            }

            return $a;
        }
        else {
            return $b;
        }
    }
}