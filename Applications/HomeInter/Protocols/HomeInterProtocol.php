<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/2/19
 * Time: 19:29
 */

namespace Protocols;


use GatewayWorker\Lib\Gateway;
use Utils\Log;
use Workerman\Worker;

class HomeInterProtocol
{
    //验证包函数
    public static function input($recv_buffer)
    {
        //如果长度小于3，则继续等待数据
        if (strlen($recv_buffer) < 3) {
            Log::log("E","recv_buffer<3");
            Log::log("E","$recv_buffer");
            return 0;
        }

        //取包的前两个字节，判断包的完整度
        $data_len = unpack("ndata_len", $recv_buffer)['data_len'];
        Log::log("E",'$data_len:'."$data_len");
        Log::log("E","strlen:".(strlen($recv_buffer)-2));
        Log::log("E",'--------------$data:-----');
        Log::log("E",'$data:'."$recv_buffer");
        Log::log("E",'--------------$data:-----');
        if($data_len==strlen($recv_buffer)-2)
        {
            return $data_len+2;
        }
        else{
            return false;
        }
    }

    //解包
    public static function decode($recv_buffer)
    {
        return json_decode(substr($recv_buffer, 2),true);
    }


    //打包
    public static function encode($data)
    {

        Log::log("D",'$data:'.$data);
        // 计算整个包的长度，首部2字节+包体字节数
        $total_length = strlen($data);
        Log::log("D",'$total_length:'.$total_length);
        // 返回打包的数据
        return pack('n', $total_length) . $data;
    }


}