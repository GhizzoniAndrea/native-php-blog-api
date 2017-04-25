<?php

require_once('models/CommentModel.php');
require_once('models/TokenModel.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/22 0022
 * Time: 13:56
 */
class Comment
{

    public function __construct()
    {
        $this->commentModel = new CommentModel();
        $this->tokenModel = new TokenModel();
    }

    public function postComment()
    {
        $res = array(
            'status' => 400,
            'message' => '新增评论失败',
            'data' => new stdClass()
        );
        $token = $_POST['token'];
        $articleId = $_POST['article_id'];
        $parentId = $_POST['parent_id'];
        $content = $_POST['content'];
        if ($token) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        if ($articleId) {
            $res['message'] = '文章ID参数不能为空！';
            echo json_encode($res, true);
            return;
        }
        if ($parentId) {
            $parentId = 0;
        }
        if ($content) {
            $res['message'] = '内容参数不能为空！';
            echo json_encode($res, true);
            return;
        }
        $user = $this->tokenModel->getToken($token);
        if (empty($user)) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        $bool = $this->commentModel->addComment($user['user_id'], $articleId, $content, $parentId);
        if ($bool) {
            $res['status'] = 200;
            $res['message'] = '新增评论成功';
        }
        echo json_encode($res, true);
    }

    public function deleteComment()
    {
        $res = array(
            'status' => 400,
            'message' => '删除评论失败',
            'data' => new stdClass()
        );
        $token = $_POST['token'];
        $commentId = $_POST['id'];
        if ($token) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        if ($commentId) {
            $res['message'] = '评论ID参数不能为空！';
            echo json_encode($res, true);
            return;
        }
        $rows = $this->commentModel->getCommentList($commentId);
        foreach ($rows as $row) {
            $this->commentModel->deleteCommentById($rows['id']);
        }
        $res['status'] = 200;
        $res['message'] = '删除评论成功';
        echo json_encode($res, true);
    }
}