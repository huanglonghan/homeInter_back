<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/2/21
 * Time: 17:11
 */

namespace HTTPConnection;


use GatewayWorker\Lib\Db;
use Utils\Log;
use Utils\ReturnMsg;
use Utils\Tools;

class HTTPHandle
{

    //登陆消息超时
    public static $loginTimeOut = "1*60";

    //调用路径
    public static $invokePatch = array('login' => 'onLogin',
        'registry' => 'onRegistry',
        'init' => 'onInitUser',
        'getNickname' =>'onGetNickname',
        'setNickname' =>'onSetNickname'
    );

    /**
     * @param $message
     * @return string
     * @throws \Exception
     */
    public static function onMessage($message)
    {
        Log::log("D", json_encode($message));
        if (!isset($message['opt']) || !array_key_exists($message['opt'], self::$invokePatch)) {
            Log::log("D", "array_key_exists");
            return ReturnMsg::returnMsg(ReturnMsg::OPT);
        }

        if(empty($message['timestamp'])){
            return ReturnMsg::returnMsg(ReturnMsg::TIMESTAMP);
        }

        //检查时效性
        if (time() - $message['timestamp'] > self::$loginTimeOut) {
            return ReturnMsg::returnMsg(ReturnMsg::TIMEOUT);
        }

        switch ($message['opt'])
        {
            case 'otherLogin':
                return Login::otherLogin($message);
                break;
            case 'login':
                break;
            case 'setUserInfo':
                return User::setUserInfo($message);
                break;
            case 'getUserInfo':
                return User::getUserInfo($message);
                break;
            default:
                break;
        }
    }

//
//
//    /**
//     * @param $message
//     * @return string
//     * @throws \Exception
//     */
//    public static function onLogin($message)
//    {
//        if (!isset($message['mobile'], $message['passwd'], $message['timestamp'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER);
//        }
//        $ret = self::checkData($message['mobile'], $message['passwd'], $message['timestamp']);
//        if ($ret !== 'true') {
//            return $ret;
//        }
//        Log::log("W", "instance", __METHOD__);
//        $user = Db::instance('user');
//        $data = $user->select('accountID,mobile,passwd')->from('user')->where("mobile=" . $message['mobile'])->query();
//        $count = count($data);
//        if ($count == 0) {
//            Log::log("W", "0", __METHOD__);
//            return ReturnMsg::returnMsg(ReturnMsg::NONE);
//        } elseif ($count > 1) {
//            Log::log("W", ">1", __METHOD__);
//            return ReturnMsg::returnMsg(ReturnMsg::NON_UNIQUE);
//        }
//
//        if ($data[0]['mobile'] != $message['mobile'] || $data[0]['passwd'] != $message['passwd']) {
//            Log::log("W", "!=", __METHOD__);
//            return ReturnMsg::returnMsg(ReturnMsg::LOGIN_ERROR);
//        }
//        $tokenCode = Tools::genTokenCode();
//
//        Log::log("I", "Info");
//        Log::log("W", "tokenCode:$tokenCode", __METHOD__);
//        return ReturnMsg::returnMsg(ReturnMsg::SUCCESS, $tokenCode);
//
//
//    }
//
//    /**
//     * @param $message
//     * @return string
//     * @throws \Exception
//     */
//    public static function onRegistry($message)
//    {
//        if (!isset($message['mobile'], $message['passwd'], $message['timestamp'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER);
//        }
//        $ret = self::checkData($message['mobile'], $message['passwd'], $message['timestamp']);
//        if ($ret !== 'true') {
//            return $ret;
//        }
//        Log::log("D", $message['timestamp'], __METHOD__);
//        $user = Db::instance('user');
//        $data = $user->query(sprintf("SELECT mobile FROM `user` WHERE mobile='%s'", $message['mobile']));
//        $count = count($data);
//        if ($count > 0) {
//            return ReturnMsg::returnMsg(ReturnMsg::ALREADY_REGISTRY);
//        }
//        $data = $user->query(sprintf("INSERT INTO `user` SET mobile='%s',passwd='%s',registryTime=%u", $message['mobile'], $message['passwd'], $message['timestamp']));
//        Log::log("D", var_export($data), __METHOD__);
//        return ReturnMsg::returnMsg(ReturnMsg::SUCCESS);
//    }
//
//    /**
//     * @param $mobile
//     * @param $password
//     * @param $timestamp
//     * @return string
//     * @internal param $message
//     */
//    public static function checkData($mobile, $password, $timestamp)
//    {
//        if (!is_string($mobile) || !is_string($password)) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER);
//        }
//        if (empty($mobile) || empty($password) || empty($timestamp)) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER);
//        }
//        if (time() - $timestamp > self::$loginTimeOut) {
//            return ReturnMsg::returnMsg(ReturnMsg::TIMESTAMP);
//        }
//        return 'true';
//    }
//
//    /**
//     * @param $message
//     * @return string
//     *
//     * 前期使用后期将弃用
//     */
//    public static function onInitUser($message)
//    {
//        if (!isset($message['deviceId'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '未传设备id');
//        }
//        if (!isset($message['time'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '无时间戳');
//        }
//        if (time() - $message['time'] > self::$loginTimeOut) {
//            return ReturnMsg::returnMsg(ReturnMsg::TIMESTAMP);
//        }
//
//        $user = Db::instance('user');
//        $data = $user->query(sprintf("SELECT id,deviceId,uid,tokencode FROM `user` WHERE deviceId='%s'", $message['deviceId']));
//        $count = count($data);
//        if ($count > 1) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '存在两条相同的数据');
//        } elseif ($count == 1) {
//            return ReturnMsg::returnMsg(ReturnMsg::SUCCESS,
//                array('uid' => $data[0]['uid'],
//                    'tCode' => $data[0]['tokenCode'],
//                    'nickName' => $data[0]['uid']));
//        } elseif ($count < 1) {
//            $data = $user->query("SELECT id FROM `user` ORDER BY id DESC limit 1 ");
//            $count = count($data);
//            if($count<1){
//                $uid = 'HI0000001';
//            }else{
//                $uid = 'HI000000'.($data[0]['id']+1);
//            }
//
//            $tokenCode = Tools::genTokenCode();
//            try{
//                $data = $user->query(sprintf("INSERT INTO `user` SET uid='%s',deviceId='%s',tokencode='%s',nickname='%s',registryTime=%u",
//                    $uid,$message['deviceId'],$tokenCode,$uid,time()));
//            }catch(\Exception $e){
//                Log::log("D","数据库操作异常");
//                return ReturnMsg::returnMsg(ReturnMsg::OTHER,
//                    '',"数据库操作异常");
//            }
//            Log::log("D", var_export($data), __METHOD__);
//            return ReturnMsg::returnMsg(ReturnMsg::SUCCESS,
//                array('uid'=>$uid,
//                    'tCode'=>$tokenCode));
//
//        }
//        Log::log("D", var_export($data), __METHOD__);
//        return ReturnMsg::returnMsg(ReturnMsg::SUCCESS);
//
//    }

}