<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/4/24
 * Time: 18:00
 */

namespace HTTPConnection;


use Config\Db;
use Utils\ReturnMsg;

class User
{

    public static $sql_setUserInfo = "update `user` set %s where uid='%s'";
    public static $sql_getUserInfo = "select %s from `user` where uid='%s'";
    public static function setUserInfo($message)
    {
        //attributes {nickname,headImg,mail,mobile,status,validity}
        if(!isset($message['uid'],$message['attributes'])){
            return ReturnMsg::returnMsg(ReturnMsg::OTHER);
        }

        $content = '';
        $attributes = explode(",",$message['attributes']);
        foreach($attributes as $value){
            switch($value){
                case 'nickname':
                    if(!isset($message['nickname'])){
                        ReturnMsg::returnMsg(ReturnMsg::NICKNAME_IS_NULL);
                    }
                    $content =$content.sprintf("nickname='%s',",$message['nickname']);
                    break;
                case 'headImg':
                    if(!isset($message['headImg'])){
                        ReturnMsg::returnMsg(ReturnMsg::OTHER,'','headImg未传');
                    }
                    $content =$content.sprintf("headImg='%s',",$message['headImg']);
                    break;
                case 'mail':
                    if(!isset($message['mail'])||empty($message['mail'])||substr_count($message['mail'],'@')==0){
                        ReturnMsg::returnMsg(ReturnMsg::OTHER,'','mail不合法');
                    }
                    $content =$content.sprintf("mail='%s',",$message['mail']);
                    break;
                case 'mobile':
                    if(!isset($message['mobile'])||empty($message['mobile'])||strlen($message['mobile'])!=11){
                        ReturnMsg::returnMsg(ReturnMsg::OTHER,'','mobile不合法');
                    }
                    $content =$content.sprintf("mobile='%s',",$message['mobile']);
                    break;
                case 'status':
                    if(!isset($message['status'])||substr_count("0,1,2,3,4",$message['validity'])==0){
                        ReturnMsg::returnMsg(ReturnMsg::OTHER,'','status不合法');
                    }
                    $content =$content.sprintf("status='%s',",$message['status']);
                    break;
                case 'validity':
                    if(!isset($message['validity'])||substr_count("0,1",$message['validity'])==0){
                        ReturnMsg::returnMsg(ReturnMsg::OTHER,'','validity不合法');
                    }
                    $content =$content.sprintf("validity='%s',",$message['validity']);
                    break;
            }
            $content = substr_replace($content,'',strripos($content,','));
            $user = Db::instance('user');
            $data = $user->query(sprintf(self::$sql_setUserInfo,$content,$message['uid']));
            print_r("--------------------------------------------------");
            print_r($data);
        }

    }

    public static function getUserInfo($message)
    {
        //attributes {nickname,headImg,mail,mobile,status,validity}
        if(!isset($message['uid'],$message['attributes'])){
            return ReturnMsg::returnMsg(ReturnMsg::OTHER);
        }

        $content = '';
        $attributes = explode(",",$message['attributes']);
        foreach($attributes as $value) {
            switch ($value) {
                case 'nickname':
                    $content = $content . "nickname,";
                    break;
                case 'headImg':
                    $content = $content . "headImg,";
                    break;
                case 'mail':
                    $content = $content . "mail,";
                    break;
                case 'mobile':
                    $content = $content . "mobile,";
                    break;
                case 'status':
                    $content = $content . "status,";
                    break;
                case 'validity':
                    $content = $content . "validity,";
                    break;
            }
            $content = substr_replace($content, '', strripos($content, ','));
            $user = Db::instance('user');
            $data = $user->query(sprintf(self::$sql_setUserInfo, $content, $message['uid']));
            $count = count($data);
            if($count==1){
                return ReturnMsg::returnMsg(ReturnMsg::SUCCESS,json_encode($data[0]));
            }
            return ReturnMsg::returnMsg(ReturnMsg::OTHER);
        }
    }

//    public static function onGetNickname($message)
//    {
//        if (!isset($message['uid'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '未传用户id');
//        }
//        if (!isset($message['time'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '无时间戳');
//        }
//
//
//        $user = Db::instance('user');
//        $data = $user->query(sprintf("SELECT nickname FROM `user` WHERE uid='%s'", $message['uid']));
//        $count = count($data);
//        if ($count > 1) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '存在两条相同的数据');
//        } elseif ($count == 1) {
//            return ReturnMsg::returnMsg(ReturnMsg::SUCCESS,
//                array('nickname' => $data[0]['nickname']));
//        }else{
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '不存在的用户');
//        }
//    }
//
//    public static function onSetNickName($message)
//    {
//        if (!isset($message['uid'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '未传用户id');
//        }
//        if (!isset($message['nickname'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '未传昵称');
//        }
//        if (!isset($message['time'])) {
//            return ReturnMsg::returnMsg(ReturnMsg::OTHER, '', '无时间戳');
//        }
//        if (time() - $message['time'] > self::$loginTimeOut) {
//            return ReturnMsg::returnMsg(ReturnMsg::TIMESTAMP);
//        }
//
//        $user = Db::instance('user');
//        $sql =sprintf("UPDATE `user` SET nickname='%s' WHERE uid='%s'", $message['nickname'],$message['uid']);
//        $data = $user->query($sql);
//        $count = count($data);
//        var_export($count);
//        return ReturnMsg::returnMsg(ReturnMsg::SUCCESS);
//
//    }
}