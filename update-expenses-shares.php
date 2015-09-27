<?php

require __DIR__ . '/bootstrap.php';

$data = new CashMoney\Data\Data();
$action = isset($_GET['action']) ? $_GET['action'] : null;

$trip = $data->getTrip($_POST['tripID']);
$expense = $data->getExpenseByID($_POST['expenseId']);
$users = $expense->getUsedBy();

switch ($action) {
	case "add":
		$existingUsers = array_filter($users, function($el) {return $el->getID()==$_POST['debtorID'];}); // THIS ARRAY FILTER IS THE OPPOSITE OF THE OTHER ONE
		// Only add the user to the expense if they aren't already listed.
		if (!$existingUsers) {
			$users[] = $data->getUserByID($_POST['debtorID']);
		}
		break;
	case "remove":
		$users = array_values(array_filter($users, function($el) {return $el->getID()!=$_POST['debtorID'];})); // THIS ARRAY FILTER IS THE OPPOSITE OF THE OTHER ONE
		break;
}

$expense->setUsedBy($users);
$data->save();

// super fragile
$expenses = $trip->getExpenses();
$payments = $trip->getPayments(true);

header("content-type:application/json");
echo json_encode([$data->getExpenses(), $data->getUsers(), $payments]);
