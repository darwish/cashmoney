<?php

define('ROOT_DIR', __DIR__ . '/');
chdir(ROOT_DIR);

require ROOT_DIR . "vendor/autoload.php";

// I need this function for debugging
function pr($a) {
	echo '<pre>', gettype($a), ' - ', print_r($a, true), '</pre>';
}

// Pretty errors
$isCLI = !isset($_SERVER['REQUEST_URI']);

$whoops = new \Whoops\Run;
$handler = $isCLI ? new \Whoops\Handler\PlainTextHandler : new \Whoops\Handler\PrettyPageHandler;
$whoops->pushHandler($handler);
$whoops->register();
