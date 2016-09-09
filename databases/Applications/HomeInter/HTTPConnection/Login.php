<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/4/22
 * Time: 18:38
 */

namespace HTTPConnection;


use Config\Db;
use Utils\ReturnMsg;

class Login
{

    public static $sql_getUserInfo = "select uid,deviceId from `user`" .
    "where openid='%s' and mode=%u and status=0 and validity=1 limit 1";
    public static $sql_setUserInfo = "insert into `user` set uid='%s',deviceId='s',openid='%s',mode=%u,nickname='%s',headImg='%s'";

    public static function otherLogin($message)
    {
        if (!isset($message['deviceid'], $message['openid'], $message['mode'], $message['nickname'])) {
            return ReturnMsg::returnMsg(ReturnMsg::OTHER);
        }

        if (empty($message['openid'])) {
            return ReturnMsg::returnMsg(ReturnMsg::OPENID);
        }

        if (empty($message['deviceid'])) {
            return ReturnMsg::returnMsg(ReturnMsg::DEVICEID);
        }

        switch ($message['mode']) {
            case 1: //qq登录
            case 2: //微信登录
                break;
            default:
                return ReturnMsg::returnMsg(ReturnMsg::MODE);
        }

        $user = Db::instance('user');
        $data = $user->query(sprintf(self::$sql_getUserInfo, $message['openid'], $message['mode']));
        $count = count($data);
        if ($count == 0) {
            $uid = self::genUid($user);
            $user->query(sprintf(self::$sql_setUserInfo, $uid, $message['deviceid'], $message['openid'], $message['mode'], $message['nickname'], $message['headImg']));
            return ReturnMsg::returnMsg(ReturnMsg::SUCCESS,$uid);
        }elseif($count>0){
            return ReturnMsg::returnMsg(ReturnMsg::SUCCESS,$data[0]['uid']);
        }

    }

    public static $sql_getLastUid = "SELECT id FROM `user` ORDER BY id DESC limit 1 ";

    public static function genUid($user)
    {
        $uid = 'HI0000001';
        $data = $user->query(self::$sql_getLastUid);
        $count = count($data);
        if ($count == 1) {
            $uid = 'HI00000000' . ($data[0]['id'] + 1);
        }
        return $uid;
    }

}