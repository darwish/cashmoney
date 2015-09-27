<?php

require __DIR__ . '/bootstrap.php';

$latitude = isset($_GET['latitude']) ? (int)$_GET['latitude'] : null;
$longitude = isset($_GET['longitude']) ? (int)$_GET['longitude'] : null;


$locationAPI = new \CashMoney\MasterCardAtmLocations();

$results = $locationAPI->findAtms($latitude, $longitude);
header('Content-Type: application/json');
echo $results;