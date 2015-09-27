<?php

require __DIR__ . '/bootstrap.php';

$data = new CashMoney\Data\Data();

header("Content-type: application/json");
echo json_encode($data->getData());