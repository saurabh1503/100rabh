<?php
/**
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://www.manadev.com/license  Proprietary License
 */

namespace Manadev\LayeredNavigation;

use Magento\Framework\App\RequestInterface;

class RequestParser {
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var UrlSettings
     */
    protected $urlSettings;

    public function __construct(RequestInterface $request, UrlSettings $urlSettings) {
        $this->request = $request;
        $this->urlSettings = $urlSettings;
    }

    /**
     * @param string $paramName
     * @return string[]|bool
     */
    public function readMultipleValueInteger($paramName) {
        if (!($values = $this->request->getParam($paramName))) {
            return false;
        }

        if (is_array($values)) {
            return false;
        }

        $values = urldecode($values);
        $values = preg_replace($this->urlSettings->getReplaceableParameterPattern(), '', $values);

        $result = [];
        foreach (explode($this->urlSettings->getMultipleValueSeparator(), $values) as $value) {
            if ($value === false || $value === null || $value === '') {
                continue;
            }

            if (is_numeric($value)) {
                $result[] = $value;
            }
        }

        return count($result) ? $result : false;
    }

    /**
     * @param string $paramName
     * @return string|bool
     */
    public function readSingleValueInteger($paramName) {
        if (!($value = $this->request->getParam($paramName))) {
            return false;
        }

        if (is_array($value)) {
            return false;
        }

        $value = urldecode($value);
        $value = preg_replace($this->urlSettings->getReplaceableParameterPattern(), '', $value);
        if ($value === false || $value === null || $value === '') {
            return false;
        }

        return is_numeric($value) ? $value : false;
    }

    public function readMultipleValueRange($paramName) {
        if (!($values = $this->request->getParam($paramName))) {
            return false;
        }

        if (is_array($values)) {
            return false;
        }

        $values = urldecode($values);
        $values = preg_replace($this->urlSettings->getReplaceableParameterPattern(), '', $values);

        $result = [];
        $rangeRegex = $this->urlSettings->getRangeParameterPattern();
        foreach (explode($this->urlSettings->getMultipleValueSeparator(), $values) as $value) {
            if ($value === false || $value === null || $value === '') {
                continue;
            }

            if (preg_match($rangeRegex, $value, $matches)) {
                if ($matches[1] === '' || $matches[2] === '' || (float)$matches[1] < (float)$matches[2]) {
                    $result[] = [$matches[1], $matches[2]];
                }
                else {
                    $result[] = [$matches[2], $matches[1]];
                }
            }
        }

        return count($result) ? $result : false;
    }

}