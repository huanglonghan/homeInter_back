<?php
/**
 * Created by PhpStorm.
 * User: 龙汗
 * Date: 2016/2/22
 * Time: 12:45
 */

namespace Utils;


class ReturnMsg
{
    const SUCCESS = 'success';
    const OTHER = 'other';
    const LOGIN_ERROR = 'login_error';
    const OPT = 'opt';
    const TIMESTAMP = 'timestamp';
    const NONE = 'none';
    const NON_UNIQUE = 'non_unique';
    const ALREADY_REGISTRY = 'already_registry';
    const NOT_ONLINE = 'not_online';
    const DATABASE_ERROR = 'database_error';
    const OPENID = 'openid';
    const DEVICEID = 'deviceid';
    const MODE = 'mode';
    const TIMEOUT = 'timeout';
    const NICKNAME_IS_NULL = 'nickname_is_null';

    protected static $errorData = array(
        'success' => array('errorCode' => 0, 'errorMsg' => ''),
        'other' => array('errorCode' => 10037, 'errorMsg' => '其他错误'),
        'login_error' => array('errorCode' => 10038, 'errorMsg' => '密码错误或账户错误'),
        'opt' => array('errorCode' => 10039, 'errorMsg' => '选项有误'),
        'timestamp' => array('errorCode' => 10040, 'errorMsg' => '时间戳为空'),
        'none' => array('errorCode' => 10041, 'errorMsg' => '数据不存在'),
        'non_unique' => array('errorCode' => 10042, 'errorMsg' => '数据不唯一，系统错误'),
        'already_registry' => array('errorCode' => 10043, 'errorMsg' => '该账号不可用'),
        'not_online' => array('errorCode' => 1044, 'errorMsg' => '没有执行连接操作'),
        'database_error' => array('errorCode' => 1045, 'errorMsg' => '数据库错误'),
        'openid' => array('errorCode' => 1046, 'errorMsg' => 'openid为空'),
        'deviceid' => array('errorCode' => 1047, 'errorMsg' => '设备d为空'),
        'mode' => array('errorCode' => 1048, 'errorMsg' => 'mode不存在'),
        'timeout' => array('errorCode' => 10049, 'errorMsg' => '请求超时'),
        'nickname_is_null' => array('errorCode' => 10050, 'errorMsg' => '昵称为空'),
    );

    public static function returnMsg($option, $result = '', $other = '')
    {
        $ret = array();
        if (!isset(self::$errorData[$option])) {
            $option = 'other';
        }
        if ($option == 'success') {
            $ret['result'] = $result;
            $ret['errorCode'] = 0;
            return json_encode($ret);
        }

        $ret['errorCode'] = self::$errorData[$option]['errorCode'];
        if ($option == 'other' && $other !== '') {
            $ret['errorMsg'] = $other;
        } else {
            $ret['errorMsg'] = self::$errorData[$option]['errorMsg'];
        }
        return json_encode($ret);
    }


}