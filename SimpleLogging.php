<?php

/**
 * Jaziel Lopez <juan.jaziel@gmail.com>
 * Software Developer
 */

namespace JLopezMX;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;


/**
 * Class SimpleLogging
 * @package JLopezMX
 */
final class SimpleLogging
{

    /**
     * @var
     */
    private static $instance;

    /**
     * SimpleLogging constructor.
     */
    protected function __construct(){}

    /**
     *
     */
    private function __clone(){}

    /**
     *
     */
    private function __wakeup(){}

    /**
     * @param int $level
     * @param string $handler
     * @param string $signature
     * @link [Customizing the log format|https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md#customizing-the-log-format]
     * @return Logger
     */
    public static function getInstance($handler = 'handler', $level = Logger::DEBUG, $signature = '[jlopez.mx]'){

        if(is_null(self::$instance)){

            $dateFormat = "Y-m-d H:i:s";

            $output = "%datetime% $signature [%level_name%] : %message%\n";

            $formatter = new LineFormatter($output, $dateFormat);

            $stream = new StreamHandler(__DIR__.'/../log/'. date('Y-m-d', time()) .'.log', $level);

            $stream->setFormatter($formatter);

            // bind it to a logger object
            self::$instance = new Logger($handler);

            self::$instance->pushHandler($stream);
        }

        return self::$instance;
    }
}