<?php

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use Config\Service;
use GatewayWorker\Lib\Gateway;
use HTTPConnection\HTTPHandle;
use TCPConnection\TCPHandle;
use Utils\Log;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {

    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    public static function onMessage($client_id, $message)
    {
        //判断是那种协议
        if ($_SERVER['GATEWAY_PORT'] == Service::SERVICE_HTTP_PORT) {
            if (isset($message['post'])) {
                //http消息处理
                $ret = HTTPHandle::onMessage($message['post']);
                if(is_array($ret)){
                    Gateway::sendToCurrentClient(json_encode($ret));
                }
                Gateway::sendToCurrentClient($ret);
                Gateway::closeCurrentClient();
                return;
            }

        } elseif ($_SERVER['GATEWAY_PORT'] == Service::SERVICE_TCP_PORT) {
            //tcp消息处理
            TCPHandle::onMessage($client_id, $message);
            return;
        }
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    public static function onClose($client_id)
    {
        TCPHandle::onClose($client_id);
    }
}
