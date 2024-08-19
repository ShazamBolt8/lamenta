<?php
$pexel_api_key = "API_KEY_HERE";
$query = $_GET["query"] ?? null;
if (!empty($query)) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.pexels.com/v1/search?query=$query&per_page=15");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: $pexel_api_key"
  ));
  $response = curl_exec($ch);
  curl_close($ch);
  echo $response;
}