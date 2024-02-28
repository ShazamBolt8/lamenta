<?php
$server_root = $_SERVER['DOCUMENT_ROOT'];
require_once("$server_root/config.php");
function sendToDiscord($title, $description, $url, $thumbnail, $author_name, $author_pfp, $hook = "YOUR_DISCORD_HOOK_GOES_HERE")
{
  $embedData = [
    'embeds' => [
      [
        'title' => $title,
        'description' => $description,
        'url' => $url,
        'color' => 6211071,
        'footer' => [
          'text' => "Check out the article"
        ],
        'image' => [
          'url' => $thumbnail,
        ],
        'thumbnail' => [
          'url' => $author_pfp,
        ],
        'author' => [
          'name' => $author_name,
        ],
      ],
    ],
  ];

  $jsonPayload = json_encode($embedData);

  $curl = curl_init($hook);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);
  curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonPayload),
  ]);
  curl_exec($curl);
  curl_close($curl);
}