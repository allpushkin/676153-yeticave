<?php
$ts = time();
$ts_midnight = strtotime('tomorrow');
$secs_to_midnight = $ts_midnight - $ts;

$hours = floor($secs_to_midnight / 3600);
$minutes = floor(($secs_to_midnight % 3600) / 60);
$time_left = $hours . ':' . $minutes;
?>
