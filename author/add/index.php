<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Author.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();

$author = new Author($conn);

if (!$author->isLogged() || !$_SESSION['is_mod']) {
  header("Location: /login/");
  exit();
}

$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $new_author_name = $_POST['author_name'] ?? null;
  $new_author_password = $_POST['author_password'] ?? null;
  $author->addAuthor($new_author_name, $new_author_password);
  $success_msg = "Author added successfully with the name \"$new_author_name\" and password $new_author_password";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>New Author |
    <?php echo $site_name; ?>
  </title>
  <?php require_once("$server_root/components/metadata.php"); ?>
  <link rel="stylesheet" href="/author/login/login.css" />
</head>

<body>
  <div class="container">
    <?php require_once("$server_root/components/navbar.php"); ?>
    <form action="" method="POST" class="box" action="">
      <img class="logo" src="<?php echo $_SESSION['pfp']; ?>" alt="<?php echo $_SESSION['name']; ?>">
      <?php
      if (!empty($success_msg)) {
        echo <<<HTML
          <span class="head2 success"><span class="material-symbols-rounded">done</span>{$success_msg}</span>
        HTML;
      }
      ?>
      <input required autocomplete="off" class="input" placeholder="Enter their name..." name="author_name"
        type="username">
      <input required autocomplete="off" class="input" placeholder="Enter their password..." type="password"
        name="author_password">
      <button class="btn" type="submit">Add author <span class="material-symbols-rounded">person_add</span></button>
    </form>
  </div>
</body>

</html>

<?php
$dbCon->closeConnection();
?>