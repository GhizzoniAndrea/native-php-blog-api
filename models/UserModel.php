<?php

require_once('helpers/DB.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/21 0021
 * Time: 19:58
 */
class UserModel
{
    private $tableName = 'user';
    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function addUser($account, $password)
    {
        if (empty($account)) {
            return false;
        }
        if (empty($password)) {
            return false;
        }

        $password = md5($password);

        $sql = "INSERT INTO $this->tableName (account,password)
                 VALUES ('$account', '$password')";
        return $this->db->execute($sql);
    }

    public function deleteUserById($userId)
    {
        if (empty($userId)) {
            return false;
        }
        $sql = "DELETE FROM $this->tableName WHERE id = $userId";
        return $this->db->execute($sql);
    }

    public function updateUserStatus($userId)
    {
        if (empty($userId)) {
            return false;
        }
        $sql = "UPDATE $this->tableName SET status = 0 WHERE id = $userId";
        return $this->db->execute($sql);
    }

    public function getUserById($userId)
    {
        if (empty($userId)) {
            return false;
        }
        $sql = "SELECT * FROM $this->tableName WHERE id = $userId";
        return $this->db->getRow($sql);
    }

    public function getUserByAccountAndPwd($account, $password)
    {
        if (empty($account)) {
            return false;
        }
        if (empty($password)) {
            return false;
        }
        $password = md5($password);
        $sql = "SELECT * FROM $this->tableName WHERE account = '$account' AND password = '$password'";
        return $this->db->getRow($sql);
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM $this->tableName";
        return $this->db->getRows($sql);
    }

    public function getUsersByPaging($page = 0, $pageSize = 10)
    {
        $start = ($page - 1) * $pageSize;
        $sql = "SELECT * FROM $this->tableName LIMIT $start,$pageSize";
        return $this->db->getRows($sql);
    }
}