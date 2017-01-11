<?php
ini_set('display_errors', '1');
error_reporting(-1);
// date_default_timezone_set('UTC');
date_default_timezone_set('Europe/Bucharest');

$date = new DateTime();

$ts = microtime(true);

$data = new stdClass();
$data->responseId = uniqid();
$data->timestamp = $ts;
$data->date = date("Y-m-d\TH:i:s", $ts);
$data->components = new stdClass();
$data->components->year = (int)date("Y", $ts);
$data->components->month = (int)date("m", $ts)-1;
$data->components->day = (int)date("j", $ts);
$data->components->hour = (int)date("G", $ts);
$data->components->minute = (int)date("i", $ts);
$data->components->second = (int)date("s", $ts);
$data->components->milisecond = (int)round(($ts - floor($ts))*1000);



header('Content-type', 'application/json');
echo json_encode($data);
