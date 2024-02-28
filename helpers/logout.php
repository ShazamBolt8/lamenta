<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/classes/Author.php");
require_once("$server_root/helpers/session.php");
create_session_if_not_exists();
Author::logout();