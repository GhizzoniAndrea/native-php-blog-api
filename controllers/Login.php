<?php

require_once('models/UserModel.php');
require_once('models/TokenModel.php');
require_once('helpers/Token.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/21 0021
 * Time: 20:01
 */
class Login
{

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenModel = new TokenModel();
    }

    public function login()
    {
        $res = array(
            'status' => 400,
            'message' => '登录失败',
            'data' => new stdClass()
        );
        $account = $_POST['account'];
        $password = $_POST['password'];
        if (empty($account)) {
            $res['message'] = '用户名不能为空！';
            echo json_encode($res, true);
            return;
        }
        if (empty($password)) {
            $res['message'] = '密码不能为空！';
            echo json_encode($res, true);
            return;
        }
        $user = $this->userModel->getUserByAccountAndPwd($account, $password);
        if (!empty($user)) {
            $token = Token::generateToken();
            $bool = $this->tokenModel->addToken($user['id'], $token, time() + 604800);
            if ($bool) {
                $res['status'] = 200;
                $res['message'] = '登录成功';
                $res['data']->token = $token;
            }
        }
        echo json_encode($res, true);
    }
}