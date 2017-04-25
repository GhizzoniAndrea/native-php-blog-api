<?php

require_once('helpers/DB.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/22 0022
 * Time: 16:27
 */
class TokenModel
{

    private $tableName = 'user_token';

    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function addToken($userId, $token, $timeout)
    {
        if (empty($userId)) {
            return false;
        }
        if (empty($token)) {
            return false;
        }
        if (empty($timeout)) {
            $timeout = time() + 604800;
        }
        $sql = "INSERT INTO $this->tableName (user_id,token,timeout) VALUES ($userId,'$token',$timeout)";
        return $this->db->execute($sql);
    }

    public function deleteToken($token)
    {
        if (empty($token)) {
            return false;
        }
        $sql = "DELETE FROM $this->tableName WHERE token = '$token'";
        return $this->db->execute($sql);
    }

    public function updateTimeout($token, $timeout)
    {
        if (empty($token)) {
            return false;
        }
        if (empty($timeout)) {
            $timeout = time() + 604800;
        }
        $modifyTime = time();
        $sql = "UPDATE $this->tableName SET timeout = $timeout,modify_time = $modifyTime WHERE token = '$token'";
        return $this->db->execute($sql);
    }

    public function updateToken($token, $timeout)
    {
        if (empty($token)) {
            return false;
        }
        if (empty($timeout)) {
            $timeout = time() + 604800;
        }
        $modifyTime = time();
        $sql = "UPDATE $this->tableName SET timeout = $timeout,token = '$token',modify_time = $modifyTime WHERE token = '$token'";
        return $this->db->execute($sql);
    }

    public function getToken($token)
    {
        if (empty($token)) {
            return false;
        }
        $sql = "SELECT * FROM $this->tableName WHERE token = $token";
        $tokenObj = $this->db->getRow($sql);
        if (!empty($tokenObj)) {
            Token::checkToken($token);
        }
        return $tokenObj;
    }
}