<?php

require __DIR__ . '/bootstrap.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;
$amount = isset($_POST['amount']) ? (float)$_POST['amount'] : null;

$auth = new CashMoney\Auth;
$paidBy = $auth->getCurrentUser();

if (!$id) {
	$id = mt_rand(1, 1e8);
}

try {
	if (!$name) {
		throw new InvalidArgumentException("Missing name");
	}

	if (!$amount) {
		throw new InvalidArgumentException("Missing amount");
	}
	if ($amount == 0) {
		throw new InvalidArgumentException("Amount must be greater than 0");
	}
} catch (InvalidArgumentException $e) {
	header("HTTP/1.1 400 Bad Request");
	echo $e->getMessage();
	die;
}

$data = new CashMoney\Data\Data();

$expense = new CashMoney\Data\Model\Expense();

$expense->setID($id);
$expense->setName($name);
$expense->setAmount($amount);
$expense->setIsPending(true);
$expense->setPaidBy($paidBy);

$data->addExpense($expense);