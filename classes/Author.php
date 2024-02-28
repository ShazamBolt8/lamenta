<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/helpers/runQuery.php");
require_once("$server_root/helpers/session.php");
require_once("$server_root/classes/Image.php");
require_once("$server_root/helpers/handlePfp.php");

class Author
{
  private $sql;
  private $params;
  private $result;
  private $conn;

  public function __construct($conn)
  {
    create_session_if_not_exists();
    $this->conn = $conn;
    $this->isLogged();
  }

  public function getAllAuthors()
  {
    $this->sql = "SELECT author_id, author_name, author_tiny_pfp, author_is_mod FROM author;";
    $this->params = [];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    foreach ($this->result['data'] as &$author) {
      $author['author_tiny_pfp'] = handlePfp($author['author_tiny_pfp'], "tiny");
    }
    return $this->result;
  }

  public static function isLogged()
  {
    create_session_if_not_exists();
    if (isset($_SESSION['isLogged']) && isset($_SESSION['id']) && isset($_SESSION['name'])) {
      return TRUE;
    }
    return FALSE;
  }

  public function login($name, $password)
  {
    $this->sql = "SELECT author_id, author_name, author_tiny_pfp, author_pfp, author_bio, author_password, author_is_mod FROM author WHERE author_name = ? LIMIT 1;";
    $this->params = [$name];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    if ($this->result['rows'] == 0) {
      return 404;
    }
    $author = $this->result['data'][0];
    if (!password_verify($password, $author['author_password'])) {
      return 401;
    }
    $_SESSION["isLogged"] = TRUE;
    $_SESSION["id"] = $author['author_id'];
    $_SESSION["name"] = $author['author_name'];
    $_SESSION["small_pfp"] = handlePfp($author['author_tiny_pfp'], "tiny");
    $_SESSION["pfp"] = handlePfp($author['author_pfp'], "original");
    $_SESSION['bio'] = $author['author_bio'];
    $_SESSION['is_mod'] = $author['author_is_mod'];
    return 200;
  }

  public static function logout()
  {
    $_SESSION = [];
    session_destroy();
  }

  public function byId($id)
  {
    $this->sql = "SELECT author_id, author_name, author_tiny_pfp, author_medium_pfp, author_pfp, author_bio, author_is_mod, author_joined FROM author WHERE author_id = ?;";
    $this->params = [$id];
    $this->result = runQuery($this->conn, $this->sql, $this->params)['data'][0];
    $this->result["author_tiny_pfp"] = handlePfp($this->result["author_tiny_pfp"], "tiny");
    $this->result["author_medium_pfp"] = handlePfp($this->result["author_medium_pfp"], "medium");
    $this->result["author_pfp"] = handlePfp($this->result["author_pfp"], "original");
    return $this->result;
  }

  public function getViews($id)
  {
    $this->sql = "SELECT SUM(article_view) AS views FROM article WHERE article_author = ?";
    $this->params = [$id];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return (int)$this->result['data'][0]['views'];
  }

  public function getNumberOfArticles($id)
  {
    $this->sql = "SELECT COUNT(article_id) AS number_of_articles FROM article WHERE article_author = ?";
    $this->params = [$id];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result['data'][0]['number_of_articles'];
  }

  public function getJoiningDate($id)
  {
    $this->sql = "SELECT author_joined FROM author WHERE author_id = ?";
    $this->params = [$id];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result['data'][0]['author_joined'];
  }

  public function updateName($name)
  {
    $this->sql = "UPDATE author SET author_name = ? WHERE author_id = ?;";
    $this->params = [$name, $_SESSION['id']];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    $_SESSION['name'] = $name;
    return $this->result;
  }

  public function updatePfp($pfp)
  {
    $pfp = new Image($pfp);
    $result = $pfp->compressAndUpload();
    $_SESSION['pfp'] = $result['original'];
    $_SESSION['small_pfp'] = $result['tiny'];
    $this->sql = "UPDATE author SET author_pfp = ?, author_tiny_pfp = ?, author_medium_pfp = ? WHERE author_id = ?;";
    $this->params = [$result['original'], $result['tiny'], $result['medium'], $_SESSION['id']];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }

  public function addAuthor($name, $password)
  {
    $this->sql = "INSERT INTO author SET author_name = ? , author_password = ?;";
    $this->params = [$name, password_hash($password, PASSWORD_DEFAULT)];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }

}