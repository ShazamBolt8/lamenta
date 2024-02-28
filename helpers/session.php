<?php
function create_session_if_not_exists()
{
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
}