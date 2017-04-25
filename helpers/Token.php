<?php

require_once('models/TokenModel.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/22 0022
 * Time: 16:23
 */
class Token
{

    public static function generateToken()
    {
        $token = md5(uniqid(md5(microtime(true)), true));//生成一个不会重复的字符串
        $token = sha1($token);//加密
        return $token;
    }

    public static function checkToken($tokenStr)
    {
        $tokenObj = new TokenModel();
        $token = $tokenObj->getToken($tokenStr);
        if (!empty($token)) {
            if (time() - intval($token['timeout']) > 604800) {
                return false;
            }
            $newTimeout = time() + 604800;
            $bool = $tokenObj->updateTimeout($tokenStr, $newTimeout);
            if ($bool) {
                return true;
            }
        }
        return false;
    }
}