<?php

// Generic stuff
define('ROOT_DIR', __DIR__ . '/../');
chdir(ROOT_DIR);

require ROOT_DIR . "vendor/autoload.php";

function pr($a) {
	echo '<pre>', gettype($a), ' - ', print_r($a, true), '</pre>';
}

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
// End generic stuff


$transfer = new CashMoney\MasterCardTransfer();
$transfer->doTransferRequestCardAccount();

pr($transfer);