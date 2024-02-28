<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Comment.php");
require_once("$server_root/helpers/getIP.php");
require_once("$server_root/helpers/formatInput.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();
$comment = new Comment($conn);
$response = [];

$commentator = formatInput($_POST['commentator']) ?? null;
$commentatorIP = inet_pton(get_client_ip());
$articleID = (int) ($_POST['articleID']) ?? null;
$commentContent = formatInput($_POST['commentContent']) ?? null;

if (isset($commentator) && isset($commentatorIP) && isset($articleID) && isset($comment) && (strlen($commentator) < 12) && (strlen($commentContent) < 800)) {
  $comment->addComment($articleID, $commentator, $commentContent, $commentatorIP);
  $dbCon->closeConnection();
  $response['status'] = 200;
  die(json_encode($response));
} else {
  $dbCon->closeConnection();
  $response['status'] = 400;
  die(json_encode($response));
}