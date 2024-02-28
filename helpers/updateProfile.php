<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Author.php");
require_once("$server_root/helpers/formatInput.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();

$result = [];

$author = new Author($conn);
if (!$author->isLogged()) {
  $dbCon->closeConnection();
  $result['status'] = 401;
  die(json_encode($result));
}

$name = formatInput($_POST['name']) ?? null;
$pfp = $_FILES['pfp'] ?? null;

if (empty($name) || strlen($name) > 12) {
  $dbCon->closeConnection();
  $result['status'] = 400;
  die(json_encode($result));
}

if ($name != $_SESSION['name']) {
  $author->updateName($name);
}

if (strlen($pfp['name']) > 0) {
  $pfp = $author->updatePfp($pfp);
}

$result['status'] = 200;
$result['name'] = $_SESSION['name'];
$result['pfp'] = $_SESSION['pfp'];

echo json_encode($result);

$dbCon->closeConnection();