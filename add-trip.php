<?php

require __DIR__ . '/bootstrap.php';

$tripID = isset($_POST['id']) ? (int)$_POST['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$tripID) {
	header("HTTP/1.1 400 Bad Request");
	echo "Invalid tripID: $tripID";
	die;
}

$data = new CashMoney\Data\Data();

$trip = $data->getTrip($tripID);

switch ($action) {
	case "add":
		
		$name = isset($_POST['name']) ? $_POST['name'] : null;
		if (empty(name)) {
			header("HTTP/1.1 400 Bad Request");
			echo "A trip without a name is like a person without a soul.";
			die;
		}

		$trip->setName($name);
		$trip->setUsers(array());
		
		$data->save();
		break;

	case "remove":
		$data->removeTrip($trip);
		break;

	default:
		header("HTTP/1.1 400 Bad Request");
		echo "Invalid action: $action";
		die;
}
