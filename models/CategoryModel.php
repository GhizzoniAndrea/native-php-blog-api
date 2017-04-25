<?php

require_once('helpers/DB.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/21 0021
 * Time: 19:59
 */
class CategoryModel
{

    private $tableName = 'category';

    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function addCategory($name)
    {
        if (empty($name)) {
            return false;
        }
        $sql = "INSERT INTO $this->tableName (name) VALUES ('$name')";
        return $this->db->execute($sql);
    }

    public function deleteCategoryById($categoryId)
    {
        if (empty($categoryId)) {
            return false;
        }
        $sql = "DELETE FROM $this->tableName WHERE id = $categoryId";
        return $this->db->execute($sql);
    }

    public function updateCategory($categoryId, $name)
    {
        if (empty($categoryId)) {
            return false;
        }
        if (empty($name)) {
            return false;
        }
        $modifyTime = date('Y-m-d H:i:s');
        $sql = "UPDATE $this->tableName SET name = '$name',modify_time = '$modifyTime' WHERE id = $categoryId";
        return $this->db->execute($sql);
    }

    public function updateCategoryStatus($categoryId, $status = 0)
    {
        if (empty($categoryId)) {
            return false;
        }
        include('ArticleModel.php');
        $articleModel = new ArticleModel();
        $row = $articleModel->getArticleByCategoryId($categoryId);
        if (empty($row)) {
            $sql = "UPDATE $this->tableName SET status = 0 WHERE id = $categoryId";
            return $this->db->execute($sql);
        } else {
            return false;
        }
    }

    public function getCategoryById($categoryId)
    {
        if (empty($categoryId)) {
            return false;
        }
        $sql = "SELECT * FROM $this->tableName WHERE id = $categoryId";
        return $this->db->getRow($sql);
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM $this->tableName";
        return $this->db->getRows($sql);
    }

    public function getCategoriesByPaging($page = 0, $pageSize = 10)
    {
        $start = ($page - 1) * $pageSize;
        $sql = "SELECT * FROM $this->tableName LIMIT $start,$pageSize";
        return $this->db->getRows($sql);
    }
}