<?php

define('ROOT_DIR', __DIR__ . '/');
chdir(ROOT_DIR);

require ROOT_DIR . "vendor/autoload.php";

// Might as well include the mastercard stuff, it's annoying to keep copy-pasting this everywhere
define('MASTERCARD_DIR', ROOT_DIR . 'vendor/darwish/mastercard-api-php/');
include_once MASTERCARD_DIR . 'common/Environment.php';
include_once MASTERCARD_DIR . 'services/MoneySend/services/TransferService.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/Transfer.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/TransferRequest.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/FundingCard.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/FundingMapped.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/FundingAmount.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/Address.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/ReceiverAddress.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/SenderAddress.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/ReceivingCard.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/ReceivingMapped.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/ReceivingAmount.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/CardAcceptor.php';

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
