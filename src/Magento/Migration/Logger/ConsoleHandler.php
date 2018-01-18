<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Migration\Logger;

/**
 * Processing logger handler creation for migration application
 */

class ConsoleHandler extends \Monolog\Handler\AbstractHandler implements \Monolog\Handler\HandlerInterface
{
    const COLOR_RESET   = '0';
    const COLOR_BLACK   = '0;30';
    const COLOR_RED     = '0;31';
    const COLOR_GREEN   = '0;32';
    const COLOR_YELLOW  = '0;33';
    const COLOR_BLUE    = '0;34';
    const COLOR_MAGENTA = '0;35';
    const COLOR_CYAN    = '0;36';
    const COLOR_WHITE   = '0;37';

    /**
     * Paint the message to specified color
     *
     * @param string $string
     * @param string $color
     * @return string
     */
    protected function colorize($string, $color)
    {
        return "\x1b[{$color}m" . $string . "\x1b[" . self::COLOR_RESET . "m";
    }

    /**
     * {@inheritdoc}
     */
    public function handle(array $record)
    {
        if (!$this->isHandling($record)) {
            return false;
        }
        $record['formatted'] = $this->getFormatter()->format($record);
        switch ($record['level']) {
            case Logger::ERROR:
            case Logger::CRITICAL:
                echo $this->colorize($record['formatted'], self::COLOR_RED) . PHP_EOL;
                break;
            case Logger::WARNING:
                echo $this->colorize($record['formatted'], self::COLOR_YELLOW) . PHP_EOL;
                break;
            default:
                echo $record['formatted'] . PHP_EOL;
        }
        return false === $this->bubble;
    }
}
