<?php

define('ROOT_DIR', __DIR__ . '/');
chdir(ROOT_DIR);

require ROOT_DIR . "vendor/autoload.php";

// I need this function for debugging
function pr($a) {
	echo '<pre>', gettype($a), ' - ', print_r($a, true), '</pre>';
}

// Pretty errors
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
