<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Article.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();

$article = new Article($conn);
$articleData = [];

$input = $_GET["search"] ?? null;
$author = $_GET['author'] ?? null;
$category = $_GET['category'] ?? null;

switch (true) {
  case !empty($input):
    $articleData = $article->byName($input)['data'];
    break;
  case !empty($author):
    $articleData = $article->byAuthorID($author)['data'];
    break;
  case !empty($category):
    $articleData = $article->byCategoryID($category)['data'];
    break;
  default:
    $articleData = $article->getAllArticles()['data'];
    break;
}

foreach ($articleData as &$articleItem) {
  $articleItem['url'] = $article->createURL($articleItem['article_id'], $articleItem['article_name'], $articleItem['article_is_video']);
}

echo json_encode($articleData);

$dbCon->closeConnection();