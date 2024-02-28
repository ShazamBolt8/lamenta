<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Article.php");
require_once("$server_root/libs/Parsedown.php");
require_once("$server_root/helpers/handleCopyright.php");
require_once("$server_root/helpers/handlePfp.php");
require_once("$server_root/helpers/session.php");

if (!isset($_GET['id']) && !isset($_GET['viewMethod'])) {
  header("Location: /exception/400/");
  exit();
}

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();

$parser = new Parsedown();
$parser->setSafeMode(false);

$Article = new Article($conn);

$id = (int) ($_GET['id'] ?? null);
$method = $_GET['viewMethod'] ?? null;

$article = [];

if ($method == "preview") {
  create_session_if_not_exists();
  $id = $article['article_view'] = 69;
  $article['article_name'] = $_GET['name'];
  $article['article_cover'] = $_GET['cover'];
  $article['article_copyright'] = $_GET['copyright'];
  $article['article_content'] = $_GET['content'];
  $article['article_more_url'] = $_GET['more'];
  $article['author_name'] = $_SESSION['name'];
  $article['author_tiny_pfp'] = $_SESSION['small_pfp'];
  $article['category_name'] = "you-are-beautiful";
  $article['category_img'] = 'https://i.ibb.co/gj1x85n/woman.png';
  $article['article_created'] = date('Y-m-d H:i:s');
  $articleURL = "null";
}

if (!isset($method) && isset($id)) {
  $articleData = $Article->byID($id);
  if (!$articleData['rows'] > 0 || $articleData['data'][0]['article_is_video']) {
    header("Location: /exception/404/");
    exit();
  }
  $article = $articleData['data'][0];
  $article['author_tiny_pfp'] = handlePfp($article['author_tiny_pfp'], "tiny");
  $Article->addView($id);
  $articleURL = $Article->createURL($id, $article['article_name'], $article['article_is_video']);
}

$id = ($id < 10) ? "0" . $id : $id;
$content = $parser->parse($article['article_content']);
$more = $parser->parse($article['article_more_url']);
$view = ($article['article_view'] < 10) ? "0" . $article['article_view'] : $article['article_view'];
$copyright = $parser->parse(handleCopyright($article['article_copyright']));
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
  <link rel="stylesheet" href="/articles/article.css" />

  <link rel="stylesheet" href="/styles/hljs.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
  <script>
    hljs.highlightAll();
  </script>

  <script type="text/javascript" async id="MathJax-script"
    src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
  <script type="text/javascript">
    MathJax = {
      tex: {
        inlineMath: [['$', '$']]
      }
    };
  </script>

</head>

<body>
  <div class="container">

    <?php require_once("$server_root/components/navbar.php"); ?>
    <?php
    echo <<<HTML
    <div class="article">

      <div class="box info">
        <div class="heading">
          <div class="name">
            <span class="title head1">{$article['article_name']}</span>
            <span class="category dim"><span class="material-symbols-rounded">tag</span><span class="head1">{$article['category_name']}</span></span>
          </div>
          <span class="id inset dim"><span class="material-symbols-rounded">tag</span>{$id}</span>
        </div>
        <img class="cover" src="{$article['article_cover']}">
      </div>

      <div class="box content">
      {$content}
      </div>

    </div>

    <div class="metaData">

      <div class="box author">
        <img src="{$article['author_tiny_pfp']}">
        <span class="head1 name">{$article['author_name']}</span>
      </div>

      <div class="box author">
        <img src="{$article['category_img']}">
        <span class="head1 name">{$article['category_name']}</span>
      </div>

      <div class="box meta">
        <span class="icon material-symbols-rounded">attachment</span>
        <span class="value ellipsis">{$more}</span>
      </div>

      <div class="box meta copyright">
        <span class="icon material-symbols-rounded">copyright</span>
        <span class="value ellipsis">{$copyright}</span>
      </div>

      <div class="box meta">
        <span class="icon material-symbols-rounded">calendar_month</span>
        <span class="value">{$article['article_created']}</span>
      </div>

      <div class="box meta">
        <span class="icon material-symbols-rounded">visibility</span>
        <span class="value">{$view}</span>
      </div>

      <button id="speakBtn" class="btn"><span class="text">Speak</span><span class="icon material-symbols-rounded">campaign</span></button>
      <button data-url="{$articleURL}" id="shareBtn" class="btn"><span>Share</span><span class="icon material-symbols-rounded">share</span></button>
      <button id="backBtn" class="btn">Back <span class="icon material-symbols-rounded">undo</span></button>

    </div>

    HTML;
    ?>

  </div>
  <script src="/articles/article.js"></script>
</body>

</html>
<?php
$dbCon->closeConnection();
?>