<?php
/**
 * Logger utility class.
 * 
 * @author      Bogdan Constantinescu <bog_con@yahoo.com>
 * @since       2013.08.01
 * @version     1.0
 * @link        GitHub  https://github.com/z3ppelin/BogdanYM.git
 * @licence     The MIT License (http://opensource.org/licenses/MIT); see LICENCE.txt
 */
namespace bogdanym;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'YMException.php';

class Logger
{
    /**
     * @var     \bogdanym\Logger    Self instance of the class.
     */
    private static $logger = null;

    /**
     * @var     string          Filename where logs will be written.
     */
    protected $debugFile = '';

    /**
     * @var     resource        File pointer resource.
     */
    protected $f = null;

    /**
     * @var     const int       Different log levels.
     */
    const EMERG  = 0;
    const ALERT  = 1;
    const CRIT   = 2;
    const ERR    = 3;
    const WARN   = 4;
    const NOTICE = 5;
    const INFO   = 6;
    const DEBUG  = 7;
    
    
    
    /**
     * Private constructor; initializes class members.
     */
    private function __construct()
    {
        $strDebugFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bogdanym.log';
        $this->f = fopen($strDebugFile, 'a');
        $this->debugFile = realpath($strDebugFile);
    }
    
    
    
    /**
     * Destructor; frees resources, memory, closes connections, etc....
     */
    public function __destruct()
    {
        if (is_resource($this->f)) {
            fclose($this->f);
        }
    }
    
    
    
    /**
     * Retrieve unique instance of the class; implements Singleton pattern.
     * @return \bogdanym\Logger
     * @throws  \bogdanym\YMException   If the object could not be instantiatez properly.
     */
    public static function getInstance()
    {
        if (is_null(self::$logger)) {
            self::$logger = new self();
        }
        return self::$logger;
    }
    
    
    
    /**
     * Log a message.
     * @param   string    $strMsg            The message to log.
     * @param   int       $intLogLevel       The message severity.
     */
    public static function log($strMsg, $intLogLevel = self::DEBUG)
    {
        self::getInstance()->write($strMsg, $intLogLevel);
    }
    
    
    
    /**
     * Write a message.
     * @param   string    $strMsg            The message to log.
     * @param   int       $intLogLevel       The message severity.
     */
    private function write($strMsg, $intLogLevel = self::DEBUG)
    {
        if (is_resource($this->f)) {
            $strToWrite = '[' . date('Y-m-d H:i:s') . '][';
            switch ($intLogLevel) {
                case self::DEBUG:
                    $strToWrite .= 'DEBUG';
                    break;
                case self::EMERG:
                    $strToWrite .= 'EMERG';
                    break;
                case self::CRIT:
                    $strToWrite .= 'CRIT';
                    break;
                case self::ERR:
                    $strToWrite .= 'ERR';
                    break;
                case self::WARN:
                    $strToWrite .= 'WARN';
                    break;
                case self::NOTICE:
                    $strToWrite .= 'NOTTICE';
                    break;
                case self::INFO:
                    $strToWrite .= 'INFO';
                    break;
                default:
                    $strToWrite .= 'DEBUG';
            }
            $strToWrite .= '] ' . $strMsg . PHP_EOL;
            @fwrite($this->f, $strToWrite);
        }
    }
}
