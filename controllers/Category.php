<?php

require_once('models/CategoryModel.php');
require_once('models/ArticleModel.php.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/22 0022
 * Time: 13:56
 */
class Category
{

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->articleModel = new ArticleModel();
    }

    public function postCategory()
    {
        $res = array(
            'status' => 400,
            'message' => '新增分类失败',
            'data' => new stdClass()
        );
        $name = $_POST['name'];
        if (empty($name)) {
            $res['message'] = '分类名称不能为空！';
            echo json_encode($res, true);
            return;
        }
        $bool = $this->categoryModel->addCategory($name);
        if ($bool) {
            $res['status'] = 200;
            $res['message'] = '新增分类成功';
        }
        echo json_encode($res, true);
    }

    public function deleteCategory()
    {
        $res = array(
            'status' => 400,
            'message' => '删除分类失败',
            'data' => new stdClass()
        );
        $categoryId = $_POST['id'];
        if ($categoryId) {
            $res['message'] = '分类ID参数不能为空！';
            echo json_encode($res, true);
            return;
        }
        $rows = $this->articleModel->getArticleByCategoryId($categoryId);
        if (empty($rows)) {
            $bool = $this->categoryModel->deleteCategoryById($categoryId);
            if ($bool) {
                $res['status'] = 200;
                $res['message'] = '删除分类成功';
            }
        } else {
            $res['message'] = '该分类下有文章，不能删除！';
        }
        echo json_encode($res, true);
    }
}