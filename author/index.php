<?php

$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Author.php");
require_once("$server_root/classes/Article.php");
require_once("$server_root/helpers/formatDate.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();

$author = new Author($conn);
$article = new Article($conn);

if (!$author->isLogged()) {
  header("Location: /login/");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?php echo "$_SESSION[name] | $site_name"; ?>
  </title>
  <?php require_once("$server_root/components/metadata.php"); ?>
  <link rel="stylesheet" href="/author/author.css" />
</head>

<body>
  <?php
  echo <<<HTML
      <input id="authorName" value="{$_SESSION['name']}" type="hidden">
      <input id="authorPfp" value="{$_SESSION['pfp']}" type="hidden">
    HTML;
  ?>
  <div class="container">

    <?php require_once("$server_root/components/navbar.php"); ?>

    <form id="profileUpdateForm" action="/helpers/updateProfile.php" method="POST" enctype="multipart/form-data"
      class="box profile">

      <div class="group">
        <button id="edit" type="button" class="btn">
          <span class="material-symbols-rounded">edit</span>
          <span class="text">Edit</span>
        </button>
        <button id="logout" type="button" class="btn btn-danger">
          <span class="material-symbols-rounded">logout</span>
          Logout
        </button>
      </div>
      <img id="pfpPreview" class="profile_pic" src="<?php echo $_SESSION['pfp']; ?>"
        alt="<?php echo $_SESSION['name']; ?>">
      <label id="photoBtn" for="photoPicker" class="upload btn"><span class="material-symbols-rounded">add</span>Choose
        photo</label><input name="pfp" id="photoPicker" accept="image/*" type="file"></label>
      <input name="name" autocomplete="off" id="inputName" readonly class="inset" type="name"
        placeholder="Enter your new name..." value="<?php echo $_SESSION['name']; ?>">
      <button id="submitBtn" class="btn btn-success submit" type="submit">
        <span class="text">Update profile</span>
        <span class="material-symbols-rounded">chevron_right</span>
      </button>

      <span id="statsHead" class="dim head2">
        <span class="material-symbols-rounded">data_usage</span>
        Statistics
      </span>

      <div id="stats" class="stats">

        <span class="dim name">Views<span class="material-symbols-rounded">visibility</span>
          <span class="value">
            <?php echo $author->getViews($_SESSION['id']); ?>
          </span>
        </span>

        <span class="dim name">Articles<span class="material-symbols-rounded">newspaper</span>
          <span class="value">
            <?php echo $author->getNumberOfArticles($_SESSION['id']); ?>
          </span>
        </span>

        <span class="dim name">Joined<span class="material-symbols-rounded">calendar_month</span>
          <span class="value">
            <?php echo formatDate($author->getJoiningDate($_SESSION['id'])); ?>
          </span>
        </span>

        <span class="dim name">Moderator<span class="material-symbols-rounded">shield_person</span>
          <span class="value">
            <?php echo $_SESSION['is_mod'] ? "Yes" : "No"; ?>
          </span>
        </span>

      </div>
    </form>

    <div class="options">
      <a href="/new/article/" class="btn">Create new article<span class="material-symbols-rounded">note_add</span></a>
      <a href="/new/theater/" class="btn">Create new theater<span class="material-symbols-rounded">video_call</span></a>
      <?php
      if ($_SESSION['is_mod']) {
        echo <<<HTML
          <a href="/new/author/" class="btn">Add new author<span class="material-symbols-rounded">person_add</span></a>
        HTML;
      }
      ?>
    </div>

    <div class="edit_container">

      <span class="head2 box dim"><span class="material-symbols-rounded">edit</span>Edit articles</span>

      <?php
      $articleData = $article->byAuthorID($_SESSION['id']);
      if ($articleData['rows'] > 0) {
        foreach ($articleData['data'] as $articleItem) {
          $articleEditURL = $article->createEditURL($articleItem['article_id'], $articleItem['article_name'], $articleItem['article_is_video']);
          echo <<<HTML
            <div class="article box">
              <div data-pfp="{$articleItem['article_cover_tiny']}" class="pfp"></div>
              <a href="{$articleEditURL}" class="name head1">{$articleItem['article_name']}</a>
              <span class="material-symbols-rounded">{$articleItem['category_icon']}</span>
            </div>
          HTML;
        }
      }
      ?>

    </div>

  </div>
  <script src="/author/author.js" type="module"></script>
</body>

</html>

<?php
$dbCon->closeConnection();
?>