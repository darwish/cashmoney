<?php

namespace CashMoney;

if (!defined('MASTERCARD_DIR')) {
    define('MASTERCARD_DIR', ROOT_DIR . 'vendor/darwish/mastercard-api-php/');
}
include_once MASTERCARD_DIR . 'common/Environment.php';
include_once MASTERCARD_DIR . 'services/MoneySend/services/TransferService.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/Transfer.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/TransferRequest.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/FundingCard.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/FundingMapped.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/FundingAmount.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/ReceiverAddress.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/SenderAddress.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/ReceivingCard.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/ReceivingMapped.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/ReceivingAmount.php';
include_once MASTERCARD_DIR . 'services/MoneySend/domain/CardAcceptor.php';


class MasterCardTransfer {

    private $transferService;
    private $transferRequestCard;
    private $transferRequestMapped;
    private $paymentRequestCard;
    private $paymentRequestMapped;
    private $transfer;

    private $transactionReference;

    public function __construct() {
        $testUtils = new TestUtils(\Environment::SANDBOX);
        $this->transferService = new \TransferService(TestUtils::SANDBOX_CONSUMER_KEY, $testUtils->getPrivateKey(), \Environment::SANDBOX);
        $this->transactionReference = $this->generateRandomNumber();
    }

    public function doTransferRequestCardAccount(
        $amount,
        $senderName, \Address $senderAddress, \FundingCard $fundingCard,
        $receiverName, \Address $receiverAddress, \FundingCard $receivingCard
    ) {

        $amount = $amount * 100; // Implied decimal point.

        $this->transferRequestCard = new \TransferRequest();
        $this->transferRequestCard->setLocalDate(date('md'));
        $this->transferRequestCard->setLocalTime(date('His'));
        $this->transferRequestCard->setTransactionReference($this->transactionReference);
        $this->transferRequestCard->setSenderName($senderName);

        $this->transferRequestCard->setSenderAddress($senderAddress);

        $this->transferRequestCard->setFundingCard($fundingCard);
        $this->transferRequestCard->setFundingUCAF("MjBjaGFyYWN0ZXJqdW5rVUNBRjU=1111");
        $this->transferRequestCard->setFundingMasterCardAssignedId(123456);

        $fundingAmount = new \FundingAmount();
        $fundingAmount->setValue($amount);
        $fundingAmount->setCurrency(840);
        $this->transferRequestCard->setFundingAmount($fundingAmount);
        $this->transferRequestCard->setReceiverName($receiverName);

        $this->transferRequestCard->setReceiverAddress($receiverAddress);
        $this->transferRequestCard->setReceiverPhone("1800639426");

        $this->transferRequestCard->setReceivingCard($receivingCard);

        $receivingAmount = new \ReceivingAmount();
        $receivingAmount->setValue($amount);
        $receivingAmount->setCurrency(840);
        $this->transferRequestCard->setReceivingAmount($receivingAmount);
        $this->transferRequestCard->setChannel("W");
        $this->transferRequestCard->setUCAFSupport("false");
        $this->transferRequestCard->setICA("009674");
        $this->transferRequestCard->setProcessorId("9000000442");
        $this->transferRequestCard->setRoutingAndTransitNumber(990442082);

        $cardAcceptor = new \CardAcceptor();
        $cardAcceptor->setName("My Local Bank");
        $cardAcceptor->setCity("Saint Louis");
        $cardAcceptor->setState("MO");
        $cardAcceptor->setPostalCode(63101);
        $cardAcceptor->setCountry("USA");
        $this->transferRequestCard->setCardAcceptor($cardAcceptor);
        $this->transferRequestCard->setTransactionDesc("P2P");
        $this->transferRequestCard->setMerchantId(123456);

        return $this->transferService->getTransfer($this->transferRequestCard);
    }

	private function generateRandomNumber() {
		$i = 0;
		$tmp = mt_rand(1,9);

		do {
			$tmp .= mt_rand(0, 9);
		} while(++$i < 18);

		return $tmp;
	}
}