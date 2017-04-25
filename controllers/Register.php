<?php

require_once('models/UserModel.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/21 0021
 * Time: 20:01
 */
class Register
{

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register()
    {
        $res = array(
            'status' => 400,
            'message' => '注册失败',
            'data' => new stdClass()
        );

        $account = $_POST['account'];
        $password = $_POST['password'];
        if (empty($account)) {
            $res['message'] = '账号不能为空！';
            echo json_encode($res, true);
            return;
        }
        if (empty($password)) {
            $res['message'] = '密碼不能为空！';
            echo json_encode($res, true);
            return;
        }
        if (strlen($password) < 6) {
            $res['message'] = '密码长度不能小于6位！';
            echo json_encode($res, true);
            return;
        }
        $bool = $this->userModel->addUser($account, $password);
        if ($bool) {
            $res['status'] = 200;
            $res['message'] = '注册成功';
        }
        echo json_encode($res, true);
    }

}