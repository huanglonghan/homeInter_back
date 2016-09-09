<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace GatewayWorker\Lib;

/**
 * 数据库类
 */
class Redis
{
    /**
     * 实例数组
     * @var array
     */
    protected static $instance = array();

    /**
     * 获取实例
     * @param string $config_name
     * @throws \Exception
     */
    public static function instance($config_name)
    {
        if(!isset(\Config\Redis::$$config_name))
        {
            echo "\\Config\\Redis::$config_name not set\n";
            throw new \Exception("\\Config\\Redis::$config_name not set\n");
        }

        if(empty(self::$instance[$config_name]))
        {
            $config = \Config\Redis::$$config_name;
            self::$instance[$config_name] = new \GatewayWorker\Lib\RedisConnection($config['host'], $config['port'],$config['timeout']);
        }
        return self::$instance[$config_name]->getInstance();
    }

    /**
     * 关闭redis实例
     * @param string $config_name
     */
    public static function close($config_name)
    {
        if(isset(self::$instance[$config_name]))
        {
            self::$instance[$config_name]->closeConnect();
            self::$instance[$config_name] = null;
        }
    }

    /**
     * 关闭所有redis实例
     */
    public static function closeAll()
    {
        foreach(self::$instance as $connection)
        {
            $connection->closeConnect();
        }
        self::$instance = array();
    }
}
