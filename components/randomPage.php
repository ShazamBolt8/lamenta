<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/classes/Article.php");
require_once("$server_root/classes/DatabaseConnection.php");
$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();
$article = new Article($conn);
$randomArticle = $article->randomArticle();
$url = $article->createURL($randomArticle['article_id'], $randomArticle['article_name'], $randomArticle['article_is_video']);
header("Location: $url");