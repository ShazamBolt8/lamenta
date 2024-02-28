<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
function handleCopyright($text)
{
  global $site_address;
  if (empty($text)) {
    return "{$site_address}policy/";
  } else {
    return $text;
  }
}