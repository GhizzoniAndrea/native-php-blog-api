<?php

require_once('helpers/DB.php');

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/21 0021
 * Time: 20:00
 */
class CommentModel
{

    private $tableName = 'comment';

    private $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function addComment($userId, $articleId, $content, $parentId = 0)
    {
        if (empty($userId)) {
            return false;
        }
        if (empty($articleId)) {
            return false;
        }
        if (empty($content)) {
            return false;
        }

        $sql = "INSERT INTO $this->tableName (user_id,content,parent_id) VALUES ($userId,'$content',$parentId)";
        return $this->db->execute($sql);
    }

    public function deleteCommentById($commentId)
    {
        if (empty($commentId)) {
            return false;
        }
        $sql = "DELETE FROM $this->tableName WHERE id = $commentId";
        return $this->db->execute($sql);
    }

    public function updateCommentStatusByArticleId($articleId)
    {
        if (empty($articleId)) {
            return false;
        }
        $sql = "UPDATE $this->tableName SET status = 0 WHERE article_id = $articleId";
        return $this->db->execute($sql);
    }

    public function updateCommentStatus($commentId, $status = 0)
    {
        if (empty($commentId)) {
            return false;
        }

    }

    public function getCommentsByParentId($commentId)
    {
        if (empty($commentId)) {
            return false;
        }
        $sql = "SELECT * FROM $this->tableName WHERE parent_id = $commentId";
        return $this->db->getRows($sql);
    }

    public function getAllComments()
    {
        $sql = "SELECT * FROM $this->tableName";
        return $this->db->getRows($sql);
    }

    public function getCommentsByPaging($page = 1, $pageSize = 10)
    {
        $offset = ($page - 1) * $pageSize;
        $sql = "SELECT * FROM $this->tableName LIMIT $offset,$pageSize";
        return $this->db->getRows($sql);
    }

    public function getCommentList($parentId = 0, &$result = array())
    {
        if (empty($parentId)) {
            return array();
        }
        $sql = "SELECT * FROM $this->tableName WHERE parent_id = $parentId ORDER BY create_time ASC";
        $arr = $this->db->getRows($sql);
        if (empty($arr)) {
            return array();
        }
        foreach ($arr as $comment) {
            $thisArr =& $result[];
            $comment["children"] = $this->getCommlist($comment["id"], $thisArr);
            $thisArr = $comment;
        }
        return $result;
    }
}