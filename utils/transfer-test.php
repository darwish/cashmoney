<?php

require __DIR__ . '/../bootstrap.php';

$transfer = new CashMoney\MasterCardTransfer();
$transfer->doTransferRequestCardAccount();

pr($transfer);