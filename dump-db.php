<?php

require __DIR__ . '/bootstrap.php';

$data = new CashMoney\Data\Data();

// Encoding then decoding makes it into an array, instead of an object, which is nicer for printing out.
pr(json_decode(json_encode($data->getData()), true));