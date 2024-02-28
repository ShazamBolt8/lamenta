<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/helpers/runQuery.php");

class Article
{
  private $sql;
  private $params;
  private $result;
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function getAllArticles()
  {
    $this->sql = "SELECT
                        article.article_id,
                        article.article_name,
                        article.article_created,
                        author.author_name,
                        article.article_is_video,
                        author.author_tiny_pfp,
                        category.category_icon
                     FROM
                        article
                     JOIN
                        author ON article.article_author = author.author_id
                     JOIN
                        category ON article.article_category = category.category_id
                        ORDER BY article.article_created DESC;";

    $this->params = [];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }

  public function byID($articleID)
  {
    $this->sql = "SELECT
                    article.article_id,
                    article.article_name,
                    article.article_created,
                    article.article_cover,
                    article.article_cover_tiny,
                    article.article_cover_medium,
                    article.article_is_video,
                    article.article_content,
                    article.article_category,
                    article.article_more_url,
                    article.article_copyright,
                    article.article_view,
                    article.article_author,
                    author.author_name,
                    author.author_tiny_pfp,
                    author.author_medium_pfp,
                    category.category_name,
                    category.category_icon,
                    category.category_img
                  FROM
                    article
                  JOIN
                    author ON article.article_author = author.author_id
                  JOIN
                    category ON article.article_category = category.category_id
                  WHERE
                    article.article_id = ?";
    $this->params = [$articleID];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }

  public function byName($name)
  {
    $this->sql = "SELECT
                    article.article_id,
                    article.article_name,
                    article.article_created,
                    article.article_cover,
                    article.article_is_video,
                    article.article_cover_tiny,
                    article.article_cover_medium,
                    author.author_name,
                    author.author_tiny_pfp,
                    category.category_icon
                  FROM
                    article
                  JOIN
                    author ON article.article_author = author.author_id
                  JOIN
                    category ON article.article_category = category.category_id
                  WHERE
                    article.article_name LIKE ?
                  ORDER BY article.article_created DESC;
                    ";
    $this->params = ["%$name%"];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }

  public function byAuthorID($authorID)
  {
    $this->sql = "SELECT
                    article.article_id,
                    article.article_name,
                    article.article_created,
                    article.article_is_video,
                    article.article_cover,
                    article.article_cover_tiny,
                    article.article_cover_medium,
                    author.author_name,
                    author.author_tiny_pfp,
                    category.category_icon
                  FROM
                    article
                  JOIN
                    author ON article.article_author = author.author_id
                  JOIN
                    category ON article.article_category = category.category_id
                  WHERE
                    article.article_author = ?
                  ORDER BY article.article_created DESC;
                    ";
    $this->params = [$authorID];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }

  public function byCategoryID($categoryID)
  {
    $this->sql = "SELECT
                    article.article_id,
                    article.article_name,
                    article.article_created,
                    article.article_cover,
                    article.article_is_video,
                    article.article_cover_tiny,
                    article.article_cover_medium,
                    author.author_name,
                    author.author_tiny_pfp,
                    category.category_icon
                  FROM
                    article
                  JOIN
                    author ON article.article_author = author.author_id
                  JOIN
                    category ON article.article_category = category.category_id
                  WHERE
                    article.article_category = ?
                  ORDER BY article.article_created DESC;
                    ";
    $this->params = [$categoryID];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }


  public function randomArticle()
  {
    $this->sql = "SELECT
                    article.article_id,
                    article.article_name,
                    article.article_cover_medium,
                    article.article_is_video,
                    author.author_name,
                    author.author_tiny_pfp,
                    category.category_icon
                  FROM
                    article
                  JOIN
                    author ON article.article_author = author.author_id
                  JOIN
                    category ON article.article_category = category.category_id
                  WHERE
                    article.article_is_video = FALSE
                  ORDER BY RAND() LIMIT 1;
                    ";
    $this->params = [];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result['data'][0];
  }
  public function randomVideoArticle()
  {
    $this->sql = "SELECT
                    article.article_id,
                    article.article_name,
                    article.article_content,
                    article.article_is_video,
                    author.author_name,
                    author.author_tiny_pfp,
                    category.category_icon
                  FROM
                    article
                  JOIN
                    author ON article.article_author = author.author_id
                  JOIN
                    category ON article.article_category = category.category_id
                  WHERE
                    article.article_is_video = TRUE
                  ORDER BY RAND() LIMIT 1;
                    ";
    $this->params = [];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result['data'][0];
  }

  public function createArticle($name, $cover, $cover_tiny, $cover_medium, $more, $isVideo, $authorIP, $copyright, $category, $author, $content)
  {
    $this->conn->begin_transaction();
    try {
      $this->sql = "INSERT INTO article (article_name, article_cover, article_cover_tiny, article_cover_medium, article_more_url, article_is_video, article_ip, article_copyright, article_category, article_author, article_content) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
      $this->params = [$name, $cover, $cover_tiny, $cover_medium, $more, $isVideo, $authorIP, $copyright, $category, $author, $content];
      $this->result = runQuery($this->conn, $this->sql, $this->params);
      $insertedId = $this->conn->insert_id;
      $this->conn->commit();
      return $insertedId;
    } catch (Exception $e) {
      $this->conn->rollback();
      throw $e;
    }
  }

  public function editArticle($id, $name, $cover, $cover_tiny, $cover_medium, $more, $isVideo, $authorIP, $copyright, $category, $author, $content)
  {

    $this->sql = "UPDATE article
      SET
          article_name = ?,
          article_cover = ?,
          article_cover_tiny = ?,
          article_cover_medium = ?,
          article_more_url = ?,
          article_is_video = ?,
          article_ip = ?,
          article_copyright = ?,
          article_category = ?,
          article_author = ?,
          article_content = ?
      WHERE
          article_id = ?;
      ";
    $this->params = [$name, $cover, $cover_tiny, $cover_medium, $more, $isVideo, $authorIP, $copyright, $category, $author, $content, $id];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }

  public function createURL($id, $name, $isTheater)
  {
    $name = filter_var($name, FILTER_SANITIZE_URL);
    $page = $isTheater ? "theaters" : "articles";
    return "/$page/$id/$name";
  }
  public function createEditURL($id, $name, $isTheater)
  {
    $name = filter_var($name, FILTER_SANITIZE_URL);
    $page = $isTheater ? "theater" : "article";
    return "/edit/$page/$id/$name";
  }

  public function addView($id)
  {
    $this->sql = "UPDATE article SET article_view = article_view + 1 WHERE article_id = ?";
    $this->params = [(int) $id];
    $this->result = runQuery($this->conn, $this->sql, $this->params);
    return $this->result;
  }
}