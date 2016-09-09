<?php
namespace GatewayWorker\Lib;

/**
 * 数据库连接类，依赖redis扩展
 * 在https://github.com/auraphp/Aura.SqlQuery的基础上修改而成
 */
class RedisConnection
{

    /**
     * redis 实例
     * @var redis
     */
    protected $redis;

    /**
     * 数据库用户名密码等配置
     * @var array
     */
    protected $settings = array();

    /**
     * RedisConnection constructor.
     * @param $host
     * @param $port
     * @param $timeout
     */
    public function __construct($host, $port, $timeout)
    {
        $this->settings = array(
            'host'          => $host,
            'port'          => $port,
            'timeout'          => $timeout,
        );
        $this->connect();
    }

    /**
     * 创建redis实例
     */
    protected function connect()
    {
        $this->redis = new \Redis();
        $this->redis->connect($this->settings["host"],$this->settings['port'],$this->settings['timeout']);
    }

    /**
    *   关闭连接
    */
    public function closeConnection()
    {
        $this->redis->close();
        $this->redis = null;
    }

    public function getInstance()
    {
        return $this->redis;
    }


}


