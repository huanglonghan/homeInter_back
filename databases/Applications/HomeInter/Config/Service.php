<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/2/21
 * Time: 16:43
 */

namespace Config;

class Service
{
    //分别是两种服务协议的端口
    const SERVICE_HTTP_PORT = 7272;
    const SERVICE_TCP_PORT = 8282;

    /**
     * 日志级别
     * I(Info) < D(Debug) < W(Warning) < E(Error)
     **/
    public static $logLevel = "D";
}