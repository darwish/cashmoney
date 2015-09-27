<?php

require __DIR__ . '/bootstrap.php';

$tripID = isset($_POST['tripID']) ? (int)$_POST['tripID'] : null;
$debtorID = isset($_POST['debtorID']) ? (int)$_POST['debtorID'] : null;
$lenderID = isset($_POST['lenderID']) ? (int)$_POST['lenderID'] : null;

if (!$tripID) {
	header("HTTP/1.1 400 Bad Request");
	echo "Must specify tripID";
	die;
}

$data = new CashMoney\Data\Data();

if ($debtorID && $lenderID) {
	$payment = $data->getPayment($tripID, $debtorID, $lenderID);
	doPayment($payment, $data);
} else {
	// Pay all if no debtor/lender is specified
	$trip = $data->getTrip($tripID);
	$payments = $trip->getPayments();

	foreach ($payments as $payment) {
		if (!$payment->getIsPaid()) {
			doPayment($payment, $data);
		}
	}
}

function doPayment(CashMoney\Data\Model\Payment $payment, $data) {
	$senderAddress = $payment->getDebtor()->getAddress();
	$fundingCard = $payment->getDebtor()->getCard();

	$transfer = new CashMoney\MasterCardTransfer();

	$transferRequest = $transfer->doTransferRequestCardAccount(
        $payment->getAmount(),
        $payment->getDebtor()->getName(), $payment->getDebtor()->getAddress(), $payment->getDebtor()->getCard(),
        $payment->getLender()->getName(), $payment->getLender()->getAddress(), $payment->getLender()->getCard()
	);

	$payment->setIsPaid(true);
	$data->save();
}