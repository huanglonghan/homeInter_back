<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/2/21
 * Time: 16:35
 */

namespace Config;

class Redis
{
    // Redis实例
    public static $public = array(
        'host'    => '127.0.0.1',
        'port'    => 10001,
        'timeout'    => 0,
    );
//
//    // 数据库实例2
//    public static $db2 = array(
//        'host'    => '127.0.0.1',
//        'port'    => 3306,
//        'user'    => 'mysql_user',
//        'password' => 'mysql_password',
//        'dbname'  => 'database_name2',
//        'charset'    => 'utf8',
//    );
}
