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
     * @var string
     */
    private static $defaultDateFormat = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    private static $defaultExt = 'log';

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
     * @param $logFilePath
     * @param string $handler
     * @param bool $dailyLogFileRotation
     * @param int $level
     * @param array $opts
     * @link [Customizing the log format|https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md#customizing-the-log-format]
     * @return Logger
     * @throws \Exception
     */
    public static function getInstance($logFilePath, $handler = 'MyAppLog', $dailyLogFileRotation = false, $level = Logger::DEBUG, $opts = array()){

        if(is_null(self::$instance)){

            if(empty($logFilePath))
                throw new \Exception(sprintf('Argument [%s] must be provided.', 'logFilePath'));


            if(!is_dir($logFilePath)) {

                if(!mkdir($logFilePath)){
                    throw new \Exception(sprintf('Unable to create %s. Insufficient permissions.', $logFilePath));
                }
            }

            // defaults
            $signature   = '';                // (signature)
            $logFileName = $handler;          // (filename)
            $extension   = self::$defaultExt; // (extension)
            $dateFormat  = self::$defaultDateFormat; // (date format in log file entries)

            // check for daily log file rotation argument
            if($dailyLogFileRotation){

                // daily log files rotation
                $logFileName .=  '-' . date('Y-m-d', time());
            }

            // check for custom signature argument optional
            if(isset($opts['signature'])){

                $signature = sprintf('[%1$s] -', $opts['signature']);
            }

            // check for custom extension argument optional
            if(isset($opts['extension'])){

                $extension = $opts['extension'];
            }

            // check for custom date format argument optional
            if(isset($opts['line_format_date_format'])){

                $dateFormat = $opts['line_format_date_format'];
            }

            $logFileName = $logFilePath . $logFileName . '.' . $extension;

            $output = "%datetime% $signature [%level_name%] : %message%\n";

            $formatter = new LineFormatter($output, $dateFormat);

            $stream = new StreamHandler($logFileName, $level);

            $stream->setFormatter($formatter);

            // bind it to a logger object
            self::$instance = new Logger($handler);

            self::$instance->pushHandler($stream);
        }

        return self::$instance;
    }
}