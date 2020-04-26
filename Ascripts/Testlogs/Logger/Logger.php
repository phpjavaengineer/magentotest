<?php
/**
 * @Package Module: Ascripts_Testlogs
 * @Author: Ashfaq Ahmed
 * @Email: phpjavaengineer@gmail.com
 * @Phone: +92-345-4128462
 * @copyright : April 2020
 */

namespace Ascripts\Testlogs\Logger;

class Logger extends \Monolog\Logger
{
    /**
     * @var string
     */
    const LOG_INFO = 'info';

    /**
     * @var string
     */
    const LOG_CRITITAL = 'critical';
    /**
     * @var string
     */
    const LOG_DEBUG = 'debug';
    /**
     * @var string
     */
    const LOG_ERROR = 'error';
    /**
     * @var string
     */
    const LOG_ALERT = 'alert';
    /**
     * @var string
     */
    const LOG_WARNING = 'warning';
    /**
     * @var string
     */
    const LOG_EMERGENCY = 'emergency';
    /**
     * @var string
     */
    const LOG_NOTICE = 'notice';
    /**
     * @var string
     */
    const LOG_RECORED = 'record';

    /**
     * @param String $log_msg
     * @param String|null $logType
     * @param array $params
     * @return bool
     */
    public function addLog(String $log_msg, String $logType = null, Array $params = [])
    {

        if ($logType == null) {
            $logType = self::LOG_INFO;
        }
        switch ($logType) {
            case self::LOG_INFO:
                return $this->addInfo($log_msg, $params);
                break;
            case self::LOG_ALERT:
                return $this->addAlert($log_msg, $params);
                break;
            case self::LOG_CRITITAL:
                return $this->addCritical($log_msg, $params);
                break;
            case self::LOG_DEBUG:
                return $this->addDebug($log_msg, $params);
                break;
            case self::LOG_ERROR:
                return $this->addError($log_msg, $params);
                break;
            case self::LOG_WARNING:
                return $this->addWarning($log_msg, $params);
                break;
            case self::LOG_EMERGENCY:
                return $this->addWarning($log_msg, $params);
                break;
            case self::LOG_NOTICE:
                return $this->addNotice($log_msg, $params);
                break;
            case self::LOG_RECORED:
                return $this->addRecord($log_msg, $params);
                break;
            default:
                return $this->addInfo($log_msg, $params);
        }
    }

}

