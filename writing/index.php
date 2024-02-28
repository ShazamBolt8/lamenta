<?php
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
header("Access-Control-Allow-Origin: $origin");

$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Author.php");
require_once("$server_root/classes/Category.php");
require_once("$server_root/classes/Article.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();

$author = new Author($conn);
$article = new Article($conn);

if (!$author->isLogged()) {
  header("Location: /login/");
  exit();
}

if (!isset($_GET['method'], $_GET['type'])) {
  header("Location: /exception/400/");
  exit();
}

$category = new Category($conn);
$categoryData = $category->getAllCategories()['data'];
$categoryChoices = "";
foreach ($categoryData as $categoryItem) {
  $categoryChoices .= "
        <div data-id='{$categoryItem['category_id']}' class='choice inset'>
            <div class='material-symbols-rounded dim'>{$categoryItem['category_icon']}</div>
            <span class='name head1'>{$categoryItem['category_name']}</span>
        </div>
    ";
}

$method = $_GET['method'];
$type = $_GET['type'];
$isVideo = $type == "article" ? 0 : 1;

if ($method == "create") {
  $method = "create";
  $id = "new";
  $name = $cover = $more = $copyright = $content = $category = "";
}

if ($method == "edit" && isset($_GET['id'])) {
  $articleID = $_GET['id'];
  $method = $_GET['method'];
  $articleData = $article->byID($articleID);

  if ($articleData['rows'] == 0 || $articleData['data'][0]['article_is_video'] != $isVideo) {
    header("Location: /exception/404/");
    exit();
  }

  $articleData = $articleData['data'][0];

  if ($articleData['article_author'] != $_SESSION['id']) {
    header("Location: /exception/401/");
    exit();
  }

  $id = $articleID;
  $name = $articleData['article_name'];
  $cover = $articleData['article_cover'];
  $more = $articleData['article_more_url'];
  $copyright = $articleData['article_copyright'];
  $content = $articleData['article_content'];
  $category = $articleData['article_category'];
}
if ($type == "article") {
  $namePH = "Article title...";
  $coverPH = "Article cover URL...";
  $contentPH = "Your article goes here...";
  $button = "<button data-isVideo=\"$isVideo\" id=\"previewBtn\" class=\"button btn\">Preview<span class=\"material-symbols-rounded\">live_tv</span></button>";
} else {
  $namePH = "Theater topic...";
  $coverPH = "Theater cover URL...";
  $contentPH = "Only <iframe></iframe> of the video. Nothing else!";
  $button = "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    Writing |
    <?php echo $site_name; ?>
  </title>
  <?php require_once("$server_root/components/metadata.php"); ?>
  <link rel="stylesheet" href="/writing/writing.css" />
</head>

<body>
  <div class="container">
    <?php require_once("$server_root/components/navbar.php") ?>
    <?php
    echo <<<HTML
        <div class="fields">
            <input required class="valueField" type="hidden" name="articleID" value="{$id}">
            <input required class="valueField" type="hidden" name="method" value="{$method}">
            <input required class="valueField" type="hidden" name="isVideo" value="{$isVideo}">
            <input autocomplete="off" required class="box field valueField" type="text" name="name" value="{$name}" placeholder="{$namePH}">
            <input autocomplete="off" required class="box field valueField" type="text" name="cover" value="{$cover}" placeholder="{$coverPH}">
            <input autocomplete="off" required class="box field valueField" type="text" name="more" value="{$more}" placeholder="URL to an additional resource...">
            <textarea class="box textarea valueField" name="copyright" placeholder="Copyright details (optional)...">{$copyright}</textarea>
            <input required class="valueField" id="categoryID" name="category" value="{$category}" type="hidden">
            <details id="details" class="box head2 dim field">
                <summary id="summary">Select category:</summary>
                {$categoryChoices}
            </details>
        </div>
        <div class="main">
          <textarea required class="box textarea valueField" name="content" placeholder="{$contentPH}">{$content}</textarea>
          <div class="btnContainer">
            {$button}
            <button id="backupBtn" class="button btn">Backup<span class="material-symbols-rounded">cloud_download</span></button>
            <button id="submitBtn" class="button btn btn-success"><span class="text">Submit</span><span class="material-symbols-rounded">navigate_next</span></button>
          </div>
        </div>
    HTML;
    ?>
    <div class="tools">

      <div class="box uploader">
        <div id="imageContainer" class="images">
        </div>
        <input accept="image/*" type="file" id="uploader">
        <label for="uploader" id="labelPicker" class="btn btn-success uploadBtn">
          Upload File
          <span class="material-symbols-rounded">upload</span>
        </label>
        <span id="status" class="dim status">Not uploading any photo.</span>
      </div>

      <div class="box search">
        <div class="inputContainer">
          <button id="leftBtn" class="material-symbols-rounded button btn">chevron_left</button>
          <input autocomplete="off" id="search" class="inset input" type="text"
            placeholder="Search beyond the limits...">
          <button id="rightBtn" class="material-symbols-rounded button btn">chevron_right</button>
        </div>
        <img id="previewPhoto" src="" alt="Best anime close up">
        <button data-src="" id="copySearchSrc" class="copy btn">
          Copy URL
          <span class="material-symbols-rounded">file_copy</span>
        </button>
        <span id="credit" class="dim head2 credit"></span>
      </div>

    </div>

  </div>
  <script type="module" src="/writing/writing.js"></script>
</body>

</html>
<?php
$dbCon->closeConnection();
?>