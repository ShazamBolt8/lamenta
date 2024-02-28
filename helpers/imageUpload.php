<?php

$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Author.php");
require_once("$server_root/classes/Image.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();
$author = new Author($conn);

$response = [];

$img = $_FILES['image'] ?? null;

if (!$author->isLogged()) {
  $dbCon->closeConnection();
  $response['status'] = 401;
  die(json_encode($response));
}

if (!strlen($img['name']) > 0) {
  $dbCon->closeConnection();
  $response['status'] = 400;
  die(json_encode($response));
}



$uploadImg = new Image($img);
$response['status'] = 200;
$response['url'] = $uploadImg->upload();
echo json_encode($response);

$dbCon->closeConnection();