<?php

require __DIR__ . '/bootstrap.php';

$tripID = isset($_GET['id']) ? (int)$_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;
$trip = null;

$auth = new CashMoney\Auth;
$user = $auth->getCurrentUser();
$data = new CashMoney\Data\Data();

if ($tripID) {
	$trip = $data->getTrip($tripID);	
}

switch ($action) {
	case "add":
		
		$name = isset($_GET['name']) ? $_GET['name'] : null;
		if (empty($name)) {
			header("HTTP/1.1 400 Bad Request");
			echo "A trip without a name is like a person without a soul.";
			die;
		}
			
		$trip = new CashMoney\Data\Model\Trip(mt_rand(), $name, $user);
		$data->addTrip($trip);
		$data->save();
		
		echo json_encode($trip->jsonSerialize());
		break;

	case "remove":
		$data->removeTrip($trip);
		break;
	case 'add-special':
		$name = isset($_GET['name']) ? $_GET['name'] : null;
		if (empty($name)) {
			header("HTTP/1.1 400 Bad Request");
			echo "A trip without a name is like a person without a soul.";
			die;
		}

		$trip = new CashMoney\Data\Model\Trip(mt_rand(), $name, $user);

		$expense = new CashMoney\Data\Model\Expense();
		$expense->setID(mt_rand());
		$expense->setName('Taxi');
		$expense->setAmount(40);
		$expense->setIsPending(false);
		$expense->setPaidBy($data->getUserByID(3));
		$expense->setUsedBy($data->getUsersByID(range(1,5)));

		$data->addExpense($expense);
		$trip->addExpense($expense);
		$data->addTrip($trip);
		$data->save();

		echo json_encode($trip->jsonSerialize());
		break;
	default:
		header("HTTP/1.1 400 Bad Request");
		echo "Invalid action: $action";
		die;
}
