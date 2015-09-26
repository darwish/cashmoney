<?php

define('ROOT_DIR', __DIR__ . '/../');
require ROOT_DIR . "vendor/autoload.php";

$transfer = new CashMoney\MasterCardTransfer();
$transfer->doTransferRequestCardAccount();

pr($transfer);