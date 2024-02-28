<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Article.php");
require_once("$server_root/helpers/handlePfp.php");
require_once("$server_root/classes/Comment.php");
require_once("$server_root/helpers/formatDate.php");

if (!isset($_GET['id'])) {
  header("Location: /exception/400/");
  exit();
}

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();

$Article = new Article($conn);
$Comment = new Comment($conn);

$id = (int) $_GET['id'];

$articleData = $Article->byID($id);
if (!$articleData['rows'] > 0 || !$articleData['data'][0]['article_is_video']) {
  header("Location: /exception/404/");
  exit();
}
$article = $articleData['data'][0];
$Article->addView($id);
$articleURL = $Article->createURL($id, $article['article_name'], $article['article_is_video']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?php echo "$article[article_name] | $site_name"; ?>
  </title>
  <?php require_once("$server_root/components/metadata.php"); ?>
  <link rel="stylesheet" href="/theaters/theaters.css" />
</head>

<body>
  <div class="container">

    <?php require_once("$server_root/components/navbar.php"); ?>

    <div class="box videoContainer">
      <?php echo $article['article_content']; ?>
      <div class="head1 name">
        <?php echo $article['article_name']; ?>
      </div>
      <div class="data">
        <div class="author">
          <span class="head1 name"><img src="<?php echo $article['author_tiny_pfp']; ?>">
            <?php echo $article['author_name']; ?>
          </span>
          <span class="head1 dim category"><span class="material-symbols-rounded">
              <?php echo $article['category_icon']; ?>
            </span>
            <?php echo $article['category_name']; ?>
          </span>
          <span class="head1 dim category"><span class="material-symbols-rounded">visibility</span>
            <?php echo $article['article_view']; ?>
          </span>
          <span class="head1 dim category"><span class="material-symbols-rounded">calendar_month</span>
            <?php echo $article['article_created']; ?>
          </span>
        </div>
        <div class="buttons">
          <button id="shareBtn" data-url="<?php echo $articleURL; ?>" class="btn"><span
              class="material-symbols-rounded">share</span></button>
        </div>
      </div>
    </div>

    <div class="box commentContainer">
      <div id="commentLogs" class="commentLogs">
        <?php
        $commentData = $Comment->getComments($id)['data'];
        $predefinedColors = ['#5eb1ff', '#25ff35', '#ff2525', '#be4bff', '#ffa22e'];
        $ipColors = [];
        shuffle($predefinedColors);
        foreach ($commentData as $comment) {
          $commentAuthor = $comment['comment_author'];
          $commentContent = $comment['comment_content'];
          $commentIP = $comment['comment_ip'];
          if (!isset($ipColors[$commentIP])) {
            $ipColors[$commentIP] = array_pop($predefinedColors);
          }
          $date = formatDate($comment['comment_created']);
          $color = $ipColors[$commentIP];
          echo <<<HTML
          <div class="comment">
              <div class="data">
                  <span style="color: {$color};" class="name">{$commentAuthor}</span>
                  <span class="date">{$date}</span>
              </div>
              <div class="content">{$commentContent}</div>
          </div>
          HTML;
        }
        ?>
      </div>
      <div class="input">
        <input id="commentator" placeholder="Enter your name..." type="text" class="inset">
        <textarea id="commentContent" placeholder="Enter your comment..." class="inset"></textarea>
        <button data-article="<?php echo $id; ?>" id="sendButton" class="btn"><span
            class="material-symbols-rounded">send</span></button>
      </div>
    </div>
    <script src="/theaters/theaters.js" type="module"></script>
  </div>
</body>

</html>