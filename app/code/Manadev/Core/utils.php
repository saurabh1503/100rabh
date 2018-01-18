<?php

if (!function_exists('_me')) {
    function _me() {
        $ip = [];

        if (count($ip) && !in_array($_SERVER['REMOTE_ADDR'], $ip)) {
            return false;
        }

        return true;
    }
}

/**
 * MANAdev logging function
 */
if (!function_exists('_log')) {
    function _log($message, $filename = 'mana.log') {
        if (!_me()) return;

        $filename = BP . '/var/log/' . $filename;
        $s = file_exists($filename) ? @file_get_contents($filename) : '';
        file_put_contents($filename, $s . $message . "\n");
    }
}

if (!function_exists('_logStackTrace')) {
    function _logStackTrace($filename = 'mana.log') {
        if (!_me()) return;

        try {
            throw new \Exception();
        }
        catch (\Exception $e) {
            _log($e->getTraceAsString(), $filename);
        }
    }
}