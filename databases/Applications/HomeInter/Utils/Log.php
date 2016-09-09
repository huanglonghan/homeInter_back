<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/2/19
 * Time: 23:31
 */

namespace Utils;


use Config\Service;

class Log
{
    private static $logFile = __DIR__ . '/../Log/HomeInter.log';

    private static $levelRelation = array('I' => 5,
        'D' => 4,
        'W' => 3,
        'E' => 2);

    private static $levelStr = array('I' => 'Info',
        'D' => 'Debug',
        'W' => 'Warning',
        'E' => 'Error');

    /**
     * 记录日志
     * @param $logLevel
     * @param string $msg
     * @param string $method
     * @param string $customFile
     */
    public static function log($logLevel, $msg, $method = '', $customFile = '')
    {

        if (!isset(self::$levelRelation[$logLevel])) {
            return;
        }

        if (self::$levelRelation[$logLevel] > self::$levelRelation[Service::$logLevel]) {
            return;
        }

        $remote_info = "";
        if (isset($_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_PORT'])) {
            $remote_info = $_SERVER['REMOTE_ADDR'] . ":" . $_SERVER['REMOTE_PORT'] . " | ";
        }

        $msg = " | " . $remote_info . self::$levelStr[$logLevel] . " | " . $method . " --> " . $msg;
        $msg = date('Y-m-d H:i:s') . $msg . "\n";

        $logPath = self::$logFile;
        if ($customFile != '' && is_string($customFile)) {
            $logPath = __DIR__ . '/../Log/' . $customFile;
        }
        file_put_contents($logPath, $msg, FILE_APPEND | LOCK_EX);
    }


}


