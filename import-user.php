<?php

require __DIR__ . '/bootstrap.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;

if (!$id) {
	$id = mt_rand(1, PHP_INT_MAX);
}

try {
	if (!$name) {
		throw new InvalidArgumentException("Missing name");
	}
} catch (InvalidArgumentException $e) {
	header("HTTP/1.1 400 Bad Request");
	echo $e->getMessage();
	die;
}

$data = new CashMoney\Data\Data();

$user = new CashMoney\Data\Model\User();

$user->setID($id);
$user->setName($name);

$data->addUser($user);