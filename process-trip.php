<?php

require __DIR__ . '/bootstrap.php';

$tripID = isset($_POST['id']) ? (int)$_POST['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;
$trip = null;

if ($tripID) {
	$trip = $data->getTrip($tripID);	
}

$auth = new CashMoney\Auth;
$user = $auth->getCurrentUser();
$data = new CashMoney\Data\Data();

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

	default:
		header("HTTP/1.1 400 Bad Request");
		echo "Invalid action: $action";
		die;
}
