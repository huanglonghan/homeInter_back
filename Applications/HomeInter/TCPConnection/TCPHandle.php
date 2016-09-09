<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/2/21
 * Time: 17:11
 */

namespace TCPConnection;


use GatewayWorker\Lib\Gateway;
use GatewayWorker\Lib\Redis;
use Utils\Log;
use Utils\ReturnMsg;
use Utils\Tools;

class TCPHandle
{

    //调用路径
    public static $invokePatch = array('heart' => 'onHeart',
        'connect'=>'onConnect',
        'update'=>'onUpdate',
        'clear'=>'onClearInvalidUser');

    public static function onMessage($client_id, $message)
    {
        Log::log("D",json_encode($message));
        if (!isset($message['opt'])|| !array_key_exists($message['opt'], self::$invokePatch)) {
            Log::log("D", "invokePatch_array_key_inexistent");
            return;
        }

        if(!isset($_SESSION['isConnect'])&&$message['opt']!='connect'){
            Gateway::sendToCurrentClient(ReturnMsg::returnMsg(ReturnMsg::NOT_ONLINE));
            return;
        }

        $ret = call_user_func('self::'.self::$invokePatch[$message['opt']], $client_id, $message);
        if ($ret != null) {
            Gateway::sendToCurrentClient($ret);
        }
    }

    public static function onHeart($client_id, $message)
    {
        Log::log("D","heart :-------------".$_SESSION['uid']."-----------------");
        $public = Redis::instance('public');
        $public->hMset($_SESSION['uid'],
            array('uid'=>$_SESSION['uid'],
                'lat'=>$message['lat'],
                'lon'=>$message['lon'],
                'accuracy'=>$message['accuracy'],
                'time'=>time()));
        $public->setTimeout($_SESSION['uid'], 10*1000);

    }

    public static function onConnect($client_id, $message)
    {
        $public = Redis::instance('public');
        $public->sAdd("onlineUserList",$message['uid']);
        $_SESSION['uid']=$message['uid'];
        $_SESSION['isConnect']=true;
    }

    public static function onUpdate($client_id, $message)
    {
        $public = Redis::instance('public');
        $userList = $public->sMembers("onlineUserList");
        $userInfo = array();
        foreach($userList as $uid){
            if($_SESSION['uid']!=$uid){
                $is_exists = $public->hExists($uid, 'uid');
                if($is_exists){
                    array_push($userInfo,$public->hGetAll($uid));
                }
            }
        }
        Log::log("D",__METHOD__,json_encode($userInfo));
        Gateway::sendToCurrentClient(
            ReturnMsg::returnMsg(
                ReturnMsg::SUCCESS,array("opt"=>"updateLocation","content"=>$userInfo)));
    }

    public static function onClose($client_id)
    {
        $public = Redis::instance('public');
        $public->sRem("onlineUserList",$_SESSION['uid']);
        $userList = $public->sMembers("onlineUserList");
        Log::log("D",__METHOD__,json_encode($userList));

        Log::log("D",__METHOD__,"-------------------OnClose----------------");
        Gateway::sendToCurrentClient(
            ReturnMsg::returnMsg(
                ReturnMsg::SUCCESS,array("opt"=>"clearInvalidUser","content"=>$userList)));
    }


    public static function onClearInvalidUser($client_id, $message){
        $public = Redis::instance('public');
        $userList = $public->sMembers("onlineUserList");
        foreach($userList as $value){
            if(!$public->exists($value)){
                $public->sRem("onlineUserList",$value);
            }
        }

        Log::log("D",__METHOD__,json_encode($userList));

        Log::log("D",__METHOD__,"-------------------onClearInvalidUser----------------");
        Gateway::sendToCurrentClient(
            ReturnMsg::returnMsg(
                ReturnMsg::SUCCESS,array("opt"=>"clearInvalidUser","content"=>$userList)));
    }

}