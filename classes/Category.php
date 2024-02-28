<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/helpers/runQuery.php");

class Category
{
  private $sql;
  private $params;
  private $result;
  private $conn;
  public function __construct($conn)
  {
    $this->conn = $conn;
  }
  public function getAllCategories()
  {
    $this->sql = "SELECT category_id, category_name, category_icon FROM category;";
    $this->params = [];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }
}