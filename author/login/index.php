<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
require_once("$server_root/classes/DatabaseConnection.php");
require_once("$server_root/classes/Author.php");

$dbCon = new DatabaseConnection();
$conn = $dbCon->getConnection();

$author = new Author($conn);

if ($author->isLogged()) {
  header("Location: /profile/");
  exit();
}
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $author_name = $_POST['author_name'] ?? null;
  $password = $_POST['author_password'] ?? null;

  $result = $author->login($author_name, $password);
  switch ($result) {
    case 200:
      header("Location: /author/");
      break;
    case 401:
      $error_msg = "The entered password is incorrect.";
      break;
    case 404:
      $error_msg = "The author with said name does not exist.";
      break;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login |
    <?php echo $site_name; ?>
  </title>
  <?php require_once("$server_root/components/metadata.php"); ?>
  <link rel="stylesheet" href="/author/login/login.css" />
</head>

<body>
  <div class="container">
    <?php require_once("$server_root/components/navbar.php"); ?>
    <form action="" method="POST" class="box" action="">
      <img class="logo" src="<?php echo $site_logo; ?>" alt="<?php echo $site_name; ?>">
      <?php
      if (!empty($error_msg)) {
        echo <<<HTML
          <span class="head2 danger"><span class="material-symbols-rounded">error</span>{$error_msg}</span>
        HTML;
      }
      ?>
      <input required autocomplete="off" class="input" placeholder="Enter your name..." name="author_name"
        type="username">
      <input required autocomplete="off" class="input" placeholder="Enter your password..." type="password"
        name="author_password">
      <button class="btn" type="submit">Sign In <span class="material-symbols-rounded">arrow_forward_ios</span></button>
    </form>
  </div>
</body>

</html>

<?php
$dbCon->closeConnection();
?>