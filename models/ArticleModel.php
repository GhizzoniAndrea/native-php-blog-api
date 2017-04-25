<?php

require_once('helpers/DB.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/21 0021
 * Time: 19:59
 */
class ArticleModel
{

    private $tableName = 'article';

    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function addArticle($userId, $title, $categoryId, $description, $content)
    {
        if (empty($userId)) {
            return false;
        }
        if (empty($title)) {
            return false;
        }
        if (empty($categoryId)) {
            return false;
        }
//        if (empty($description)) {
//            return false;
//        }
        if (empty($content)) {
            return false;
        }

        $sql = "INSERT INTO $this->tableName (user_id,title,category_id,description,content) VALUES ($userId,'$title',$categoryId,'$description','$content')";
        return $this->db->execute($sql);
    }

    public function deleteArticleById($articleId)
    {
        if (empty($articleId)) {
            return false;
        }
        $sql = "DELETE FROM $this->tableName WHERE id = $articleId";
        return $this->db->execute($sql);
    }

    public function updateArticle($articleId, $title, $categoryId, $description, $content)
    {
        if (empty($articleId)) {
            return false;
        }
        if (empty($title)) {
            return false;
        }
        if (empty($categoryId)) {
            return false;
        }
        if (empty($description)) {
            return false;
        }
        if (empty($content)) {
            return false;
        }
        $sql = "UPDATE $this->tableName SET title = '$title',category_id = $categoryId,description = '$description',content = '$content' WHERE id = $articleId";
        return $this->db->execute($sql);
    }

    public function updateArticleStatus($articleId, $status)
    {
        if (empty($articleId)) {
            return false;
        }
        if (empty($status)) {
            $status = 0;
        }
        $sql = "UPDATE $this->tableName SET status = $status WHERE id = $articleId";
        return $this->db->execute($sql);
    }

    public function getArticleById($articleId)
    {
        if (empty($articleId)) {
            return false;
        }
        $sql = "SELECT * FROM $this->tableName WHERE id = $articleId";
        return $this->db->getRow($sql);
    }

    public function getAllArticles($userId)
    {
        $sql = "SELECT * FROM $this->tableName WHERE user_id = $userId";
        return $this->db->getRows($sql);
    }

    public function getArticlesByPaging($userId, $page = 0, $pageSize = 10)
    {
        $start = ($page - 1) * $pageSize;
        $sql = "SELECT * FROM $this->tableName WHERE user_id = $userId LIMIT $start,$pageSize";
        return $this->db->getRows($sql);
    }

    public function getArticleByCategoryId($categoryId)
    {
        if (empty($categoryId)) {
            return false;
        }
        $sql = "SELECT * FROM $this->tableName WHERE category_id = $categoryId";
        return $this->db->getRows($sql);
    }
}