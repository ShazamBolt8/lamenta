<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
?>
<nav>
  <img class="logo" src="<?php echo $site_logo; ?>" alt="<?php echo $site_name; ?>">
  <a href="/"><span class="material-symbols-rounded">home</span></a>
  <a href="/random/"><span class="material-symbols-rounded">casino</span></a>
  <a href="/about/"><span class="material-symbols-rounded">help</span></a>
  <?php
  require_once("$server_root/classes/Author.php");
  if (Author::isLogged()) {
    echo <<<HTML
      <a href="/profile/"><img src="{$_SESSION['small_pfp']}" class="profile_pic" alt="{$_SESSION['name']}"></a>
    HTML;
  } else {
    echo <<<HTML
    <a href="/login/"><span class="material-symbols-rounded">person</span></a>
  HTML;
  }
  ?>
</nav>