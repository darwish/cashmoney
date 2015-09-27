<?php

require __DIR__ . '/bootstrap.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$name = isset($_POST['name']) ? $_POST['name'] : null;

$addressData = isset($_POST['address']) ? $_POST['address'] : null;
$cardData = isset($_POST['card']) ? $_POST['card'] : null;

if (!$id) {
	$id = mt_rand(1, PHP_INT_MAX);
}

try {
	if (!$name) {
		throw new InvalidArgumentException("Missing name");
	}
	if (!$addressData) {
		throw new InvalidArgumentException("Missing address");
	}
	if (!$cardData) {
		throw new InvalidArgumentException("Missing card");
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

$address = new \Address();
$address->setLine1($addressData['line1']);
$address->setLine2($addressData['line2']);
$address->setCity($addressData['city']);
$address->setCountrySubdivision($addressData['countrySubdivision']);
$address->setPostalCode($addressData['postalCode']);
$address->setCountry($addressData['country']);
$user->setAddress($address);

$fundingCard = new \FundingCard();
$fundingCard->setAccountNumber($cardData['accountNumber']);
$fundingCard->setExpiryMonth($cardData['expiryMonth']);
$fundingCard->setExpiryYear($cardData['expiryYear']);
$user->setCard($fundingCard);

$data->addUser($user);