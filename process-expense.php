<?php

require __DIR__ . '/bootstrap.php';

$expenseID = isset($_POST['id']) ? (int)$_POST['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$expenseID) {
	header("HTTP/1.1 400 Bad Request");
	echo "Invalid expenseID: $expenseID";
	die;
}

$data = new CashMoney\Data\Data();

$expense = $data->getExpenseByID($expenseID);

switch ($action) {
	case "add":
		$userIDs = isset($_POST['user']) ? $_POST['user'] : null;
		if (empty($userIDs) || !is_array($userIDs)) {
			header("HTTP/1.1 400 Bad Request");
			echo "Users cannot be empty and must be an array";
			die;
		}

		$users = $data->getUsersByID($userIDs);
		$expense->setUsedBy($users);
		$expense->setIsPending(false);

		$data->save();
		break;

	case "remove":
		$data->removeExpense($expense);
		break;

	default:
		header("HTTP/1.1 400 Bad Request");
		echo "Invalid action: $action";
		die;
}
