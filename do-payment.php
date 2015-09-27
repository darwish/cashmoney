<?php

require __DIR__ . '/bootstrap.php';

$tripID = isset($_POST['tripID']) ? (int)$_POST['tripID'] : null;
$debtorID = isset($_POST['debtorID']) ? (int)$_POST['debtorID'] : null;
$lenderID = isset($_POST['lenderID']) ? (int)$_POST['lenderID'] : null;

if (/*!$tripID || */!$debtorID || !$lenderID) {
	header("HTTP/1.1 400 Bad Request");
	echo "Must have all of: tripID, debtorID, lenderID";
	die;
}

$data = new CashMoney\Data\Data();

$payment = $data->getPayment($tripID, $debtorID, $lenderID);

$transfer = new CashMoney\MasterCardTransfer();
$transferRequest = $transfer->doTransferRequestCardAccount();

$payment->setIsPaid(true);
$data->save();

// $address = new \Address();
// $address->setLine1("123 Main Street");
// $address->setLine2("#5A");
// $address->setCity("Arlington");
// $address->setCountrySubdivision("VA");
// $address->setPostalCode(22207);
// $address->setCountry("USA");

// $fundingCard = new \FundingCard();
// $fundingCard->setAccountNumber("5184680430000006");
// $fundingCard->setExpiryMonth(11);
// $fundingCard->setExpiryYear(2018);