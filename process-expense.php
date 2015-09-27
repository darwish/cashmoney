<?php

require __DIR__ . '/bootstrap.php';

$expenseID = isset($_POST['expenseID']) ? (int)$_POST['expenseID'] : null;
$tripID = isset($_POST['tripID']) ? (int)$_POST['tripID'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$expenseID || !$tripID) {
	header("HTTP/1.1 400 Bad Request");
	echo "Must include expenseID and tripID";
	die;
}

$data = new CashMoney\Data\Data();

$trip = $data->getTrip($tripID);
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

		$trip->addExpense($expense);

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
