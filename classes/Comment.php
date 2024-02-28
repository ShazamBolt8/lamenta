<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/helpers/runQuery.php");
require_once("$server_root/helpers/getIP.php");

class Comment
{
  private $sql;
  private $params;
  private $result;
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function addComment($articleID, $commentator, $comment, $commentatorIP)
  {
    $this->sql = "INSERT INTO comment (comment_article_id, comment_author, comment_content, comment_ip) VALUES (?, ?, ?, ?);";
    $this->params = [$articleID, $commentator, $comment, $commentatorIP];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }

  public function getComments($id)
  {
    $this->sql = "SELECT comment_id, comment_ip, comment_author, comment_content, comment_created FROM comment WHERE comment_article_id = ? ORDER BY comment_created DESC;";
    $this->params = [$id];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }
}