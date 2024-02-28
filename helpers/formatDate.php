<?php
function formatDate($dateString)
{
  $timestamp = strtotime($dateString);
  $currentTimestamp = time();
  $timeDiff = $currentTimestamp - $timestamp;
  $minute = 60;
  $hour = 60 * $minute;
  $day = 24 * $hour;
  $month = 30 * $day;
  $year = 365 * $day;
  if ($timeDiff < $minute) {
    $result = floor($timeDiff) . 's ago';
  } elseif ($timeDiff < $hour) {
    $result = floor($timeDiff / $minute) . 'm ago';
  } elseif ($timeDiff < $day) {
    $result = floor($timeDiff / $hour) . 'h ago';
  } elseif ($timeDiff < $month) {
    $result = floor($timeDiff / $day) . 'd ago';
  } elseif ($timeDiff < $year) {
    $result = floor($timeDiff / $month) . 'mo ago';
  } else {
    $result = floor($timeDiff / $year) . 'y ago';
  }
  return $result;

}