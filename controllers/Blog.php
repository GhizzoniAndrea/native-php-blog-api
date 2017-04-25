<?php

require_once('models/UserModel.php');
require_once('models/ArticleModel.php');
require_once('models/CommentModel.php');
require_once('models/TokenModel.php');
require_once('models/CategoryModel.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/21 0021
 * Time: 20:02
 */
class Blog
{

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->articleModel = new ArticleModel();
        $this->commentModel = new CommentModel();
        $this->tokenModel = new TokenModel();
        $this->categoryModel = new CategoryModel();
    }


    public function postArticle()
    {
        $res = array(
            'status' => 400,
            'message' => '发布失败',
            'data' => new stdClass()
        );
        $token = $_POST['token'];
        $title = $_POST['title'];
        $categoryId = $_POST['category_id'];
        $description = $_POST['description'];
        $content = $_POST['content'];
        if (empty($token)) {
            $res['message'] = '未登录，请登录后再试';
            echo json_encode($res, true);
            return;
        }
        if (empty($title)) {
            $res['message'] = '标题不能为空！';
            echo json_encode($res, true);
            return;
        }
        if (empty($categoryId)) {
            $res['message'] = '分类不能为空！';
            echo json_encode($res, true);
            return;
        }

        $category = $this->categoryModel->getCategoryById($categoryId);
        if (empty($category)) {
            if (empty($categoryId)) {
                $res['message'] = '未找到该分类，请刷新后再试！';
                echo json_encode($res, true);
                return;
            }
        }

        if (empty($description)) {
            $res['message'] = '描述不能为空！';
            echo json_encode($res, true);
            return;
        }
        if (empty($content)) {
            $res['message'] = '内容不能为空！';
            echo json_encode($res, true);
            return;
        }
        $user = $this->tokenModel->getToken($token);
        if (empty($user)) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        $bool = $this->articleModel->addArticle($user['user_id'], $title, $categoryId, $description, $content);
        if ($bool) {
            $res['status'] = 200;
            $res['message'] = '发布成功';
        }
        echo json_encode($res, true);
    }

    public function deleteArticle()
    {
        $res = array(
            'status' => 400,
            'message' => '删除文章失败',
            'data' => new stdClass()
        );
        $token = $_POST['token'];
        $articleId = $_POST['id'];
        if (empty($token)) {
            $res['message'] = '未登录，请登录后再试';
            echo json_encode($res, true);
            return;
        }
        if (empty($articleId)) {
            $res['message'] = '文章ID参数不能为空！';
            echo json_encode($res, true);
            return;
        }
        $user = $this->tokenModel->getToken($token);
        if (empty($user)) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        $boolComment = $this->commentModel->updateCommentStatusByArticleId($articleId);
        $boolArticle = $this->articleModel->updateArticleStatus($articleId, 0);
        if ($boolComment && $boolArticle) {
            $res['status'] = 200;
            $res['message'] = '删除文章成功';
        }
        echo json_encode($res, true);
    }

    public function postComment()
    {
        $res = array(
            'status' => 400,
            'message' => '评论失败',
            'data' => new stdClass()
        );
        $token = $_POST['token'];
        $articleId = $_POST['id'];
        $content = $_POST['content'];
        $parentId = $_POST['parent_id'];
        if (empty($token)) {
            $res['message'] = '未登录，请登录后再试';
            echo json_encode($res, true);
            return;
        }
        if (empty($articleId)) {
            $res['message'] = '文章ID参数不能为空！';
            echo json_encode($res, true);
            return;
        }

        $article = $this->articleModel->getArticleById($articleId);
        if (empty($article)) {
            $res['message'] = '找不到该文章，请刷新后再试！';
            echo json_encode($res, true);
            return;
        }

        if (empty($content)) {
            $res['message'] = '内容不能为空！';
            echo json_encode($res, true);
            return;
        }
        $user = $this->tokenModel->getToken($token);
        if (empty($user)) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        $bool = $this->commentModel->addComment($user['user_id'], $content, $parentId);
        if ($bool) {
            $res['status'] = 200;
            $res['message'] = '评论成功';
        }
        echo json_encode($res, true);
    }

    public function getBlogByPaging()
    {
        $res = array(
            'status' => 400,
            'message' => '注册失败',
            'data' => array()
        );
        $token = $_POST['token'];
        $page = $_POST['page'];
        $pageSize = $_POST['pageSize'];
        if (empty($token)) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        $user = $this->tokenModel->getToken($token);
        if (empty($user)) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        $arr = $this->articleModel->getArticlesByPaging($user['user_id'], $page, $pageSize);
        $res['data'] = $arr;

        echo json_encode($res, true);
    }

    public function getAllBlog()
    {
        $res = array(
            'status' => 400,
            'message' => '注册失败',
            'data' => array()
        );
        $token = $_POST['token'];
        if (empty($token)) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        $user = $this->tokenModel->getToken($token);
        if (empty($user)) {
            $res['message'] = '登录失效，请重新登录';
            echo json_encode($res, true);
            return;
        }
        $arr = $this->articleModel->getAllArticles($user['user_id']);
        $res['data'] = $arr;

        echo json_encode($res, true);
    }
}