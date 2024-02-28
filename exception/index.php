<?php

$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");

$error = (int) $_GET['error'] ?? null;
$description = "";
switch ($error) {
  case 404:
    $description = "We even looked inside Gray's house!";
    break;
  case 400:
    $description = "Don't know how to make requests...?";
    break;
  case 401:
    $description = "Imposter Detected, Opinion rejected.";
    break;
  case 403:
    $description = "We don't do that here.";
    break;
  case 500:
    $description = "Congrats, you broke the server!";
    break;
  default:
    $error = "BRUH";
    $description = "Error on an error? Are you kidding me?";
    break;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?php echo "$error | $site_name"; ?>
  </title>
  <?php require_once("$server_root/components/metadata.php"); ?>
  <link rel="stylesheet" href="/exception/error.css" />
</head>

<body>
  <div class="container">
    <?php require_once("$server_root/components/navbar.php"); ?>
    <div class="box error_container">
      <?php
      echo <<<HTML
        <!--<img src="https://i.ibb.co/gj1x85n/woman.png" alt="Woman with hat looking far away">-->
        <img src="https://http.cat/{$error}.jpg" alt="Error {$error}">
        <span class="head1 dim">{$error}</span>
        <span class="description dim"><span class="material-symbols-rounded">sentiment_satisfied</span>{$description}</span>
      HTML;
      ?>
    </div>
  </div>
</body>

</html>