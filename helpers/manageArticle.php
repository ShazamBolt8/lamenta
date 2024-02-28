<?php

$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Author.php");
require_once("$server_root/classes/Image.php");
require_once("$server_root/classes/Article.php");
require_once("$server_root/helpers/getIP.php");
require_once("$server_root/helpers/formatInput.php");
require_once("$server_root/helpers/sendToDiscord.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();
$author = new Author($conn);
$article = new Article($conn);

$response = [];

$method = $name = $cover = $more = $copyright = $category = $content = $isVideo = "";
$authorID = $_SESSION['id'];
$authorIP = inet_pton(get_client_ip());

if (!$author->isLogged()) {
  $dbCon->closeConnection();
  $response['status'] = 401;
  die(json_encode($response));
}

if (
  isset($_POST['method'], $_POST['name'], $_POST['cover'], $_POST['more'], $_POST['copyright'], $_POST['category'], $_POST['content'], $_POST['isVideo'])
) {
  $method = formatInput($_POST['method']);
  $name = formatInput($_POST['name']);
  $cover = $_POST['cover'];
  $more = formatInput($_POST['more']);
  $copyright = formatInput($_POST['copyright']);
  $category = (int) $_POST['category'];
  $content = $_POST['content'];
  $isVideo = (int) $_POST['isVideo'];
} else {
  $dbCon->closeConnection();
  $response['status'] = 400;
  die(json_encode($response));
}

if ($method == "create") {

  $img = new Image($cover);
  $img = $img->compressAndUpload();

  $articleID = $article->createArticle($name, $img['original'], $img['tiny'], $img['medium'], $more, $isVideo, $authorIP, $copyright, $category, $authorID, $content);
  $articleURL = $article->createURL($articleID, $name, $isVideo);

  sendToDiscord($name, "Thank you for your valuable contribution, $_SESSION[name].\n We're excited to announce that $_SESSION[name] has just written a new article! Dive into their latest work and gain fresh insights. Check out the article here: $site_address$articleURL", "$site_address" . $articleURL, $img['original'], $_SESSION['name'], $_SESSION['small_pfp']);

  $dbCon->closeConnection();
  $response['status'] = 200;
  $response['url'] = $articleURL;
  die(json_encode($response));

}

if ($method == "edit") {

  $id = (int) $_POST['articleID'];
  $oldArticleData = $article->byID($id);

  if ($oldArticleData['rows'] == 0) {
    $dbCon->closeConnection();
    $response['status'] = 404;
    die(json_encode($response));
  }

  $oldArticleData = $oldArticleData['data'][0];

  if ($cover != $oldArticleData['article_cover']) {
    $img = new Image($cover);
    $img = $img->compressAndUpload();
  } else {
    $img = [];
    $img['original'] = $oldArticleData['article_cover'];
    $img['tiny'] = $oldArticleData['article_cover_tiny'];
    $img['medium'] = $oldArticleData['article_cover_medium'];
  }

  $articleID = $article->editArticle($id, $name, $img['original'], $img['tiny'], $img['medium'], $more, $isVideo, $authorIP, $copyright, $category, $authorID, $content);
  $articleURL = $article->createURL($id, $name, $isVideo);

  $dbCon->closeConnection();
  $response['status'] = 200;
  $response['url'] = $articleURL;
  die(json_encode($response));

}