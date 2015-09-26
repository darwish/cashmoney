<?php

namespace CashMoney;

define('MASTERCARD_DIR', ROOT_DIR . 'vendor/mastercard/mastercard-api-php/');

include_once MASTERCARD_DIR . 'common/Environment.php';
include_once MASTERCARD_DIR . 'Test/TestUtils.php';
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

    public function __construct() {
        $testUtils = new \TestUtils(\Environment::SANDBOX);
        $this->transferService = new \TransferService(\TestUtils::SANDBOX_CONSUMER_KEY, $testUtils->getPrivateKey(), \Environment::SANDBOX);
    }

    public function doTransferRequestCardAccount() {
        $this->transferRequestCard = new \TransferRequest();
        $this->transferRequestCard->setLocalDate("1212");
        $this->transferRequestCard->setLocalTime("161222");
        $this->transferRequestCard->setTransactionReference("4000000001010102041");
        $this->transferRequestCard->setSenderName("John Doe");

        $address = new \SenderAddress();
        $address->setLine1("123 Main Street");
        $address->setLine2("#5A");
        $address->setCity("Arlington");
        $address->setCountrySubdivision("VA");
        $address->setPostalCode(22207);
        $address->setCountry("USA");
        $this->transferRequestCard->setSenderAddress($address);

        $fundingCard = new \FundingCard();
        $fundingCard->setAccountNumber("5184680430000006");
        $fundingCard->setExpiryMonth(11);
        $fundingCard->setExpiryYear(2014);
        $this->transferRequestCard->setFundingCard($fundingCard);
        $this->transferRequestCard->setFundingUCAF("MjBjaGFyYWN0ZXJqdW5rVUNBRjU=1111");
        $this->transferRequestCard->setFundingMasterCardAssignedId(123456);

        $fundingAmount = new \FundingAmount();
        $fundingAmount->setValue(15000);
        $fundingAmount->setCurrency(840);
        $this->transferRequestCard->setFundingAmount($fundingAmount);
        $this->transferRequestCard->setReceiverName("Jose Lopez");

        $receiverAddress = new \ReceiverAddress();
        $receiverAddress->setLine1("Pueblo Street");
        $receiverAddress->setLine2("PO BOX 12");
        $receiverAddress->setCity("El PASO");
        $receiverAddress->setCountrySubdivision("TX");
        $receiverAddress->setPostalCode(79906);
        $receiverAddress->setCountry("USA");
        $this->transferRequestCard->setReceiverAddress($receiverAddress);
        $this->transferRequestCard->setReceiverPhone("1800639426");

        $receivingCard = new \ReceivingCard();
        $receivingCard->setAccountNumber("5184680430000006");
        $this->transferRequestCard->setReceivingCard($receivingCard);

        $receivingAmount = new \ReceivingAmount();
        $receivingAmount->setValue(182206);
        $receivingAmount->setCurrency(484);
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

        $this->transfer = $this->transferService->getTransfer($this->transferRequestCard);
        // $this->assertTrue($this->transfer != null);
        // $this->assertTrue($this->transfer->getTransactionReference() > 0);
        // $this->assertTrue($this->transfer->getTransactionHistory() != null);
        // $this->assertTrue($this->transfer->getTransactionHistory()->getTransaction(0)->getResponse()->getCode() == 00);
        // $this->assertTrue($this->transfer->getTransactionHistory()->getTransaction(1)->getResponse()->getCode() == 00);
    }

    public function doTransferRequestMappedAccount() {
        $this->transferRequestMapped = new \TransferRequest();
        $this->transferRequestMapped->setLocalDate("1212");
        $this->transferRequestMapped->setLocalTime("161222");
        $this->transferRequestMapped->setTransactionReference("4000000001010102042");
        $this->transferRequestMapped->setSenderName("John Doe");

        $fundingMapped = new \FundingMapped();
        $fundingMapped->setSubscriberId("examplePHPSending@email.com");
        $fundingMapped->setSubscriberType("EMAIL_ADDRESS");
        $fundingMapped->setSubscriberAlias("My Debit Card");
        $this->transferRequestMapped->setFundingMapped($fundingMapped);
        $this->transferRequestMapped->setFundingUCAF("MjBjaGFyYWN0ZXJqdW5rVUNBRjU=1111");
        $this->transferRequestMapped->setFundingMasterCardAssignedId(123456);

        $fundingAmount = new \FundingAmount();
        $fundingAmount->setValue(15000);
        $fundingAmount->setCurrency(840);
        $this->transferRequestMapped->setFundingAmount($fundingAmount);
        $this->transferRequestMapped->setReceiverName("Jose Lopez");

        $receiverAddress = new \ReceiverAddress();
        $receiverAddress->setLine1("Pueblo Street");
        $receiverAddress->setLine2("PO BOX 12");
        $receiverAddress->setCity("El PASO");
        $receiverAddress->setCountrySubdivision("TX");
        $receiverAddress->setPostalCode(79906);
        $receiverAddress->setCountry("USA");
        $this->transferRequestMapped->setReceiverAddress($receiverAddress);
        $this->transferRequestMapped->setReceiverPhone("1800639426");

        $receivingCard = new \ReceivingCard();
        $receivingCard->setAccountNumber("5184680430000006");
        $this->transferRequestMapped->setReceivingCard($receivingCard);

        $receivingAmount = new \ReceivingAmount();
        $receivingAmount->setValue(182206);
        $receivingAmount->setCurrency(484);
        $this->transferRequestMapped->setReceivingAmount($receivingAmount);
        $this->transferRequestMapped->setChannel("W");
        $this->transferRequestMapped->setUCAFSupport("false");
        $this->transferRequestMapped->setICA("009674");
        $this->transferRequestMapped->setProcessorId("9000000442");
        $this->transferRequestMapped->setRoutingAndTransitNumber(990442082);

        $cardAcceptor = new \CardAcceptor();
        $cardAcceptor->setName("My Local Bank");
        $cardAcceptor->setCity("Saint Louis");
        $cardAcceptor->setState("MO");
        $cardAcceptor->setPostalCode(63101);
        $cardAcceptor->setCountry("USA");
        $this->transferRequestMapped->setCardAcceptor($cardAcceptor);
        $this->transferRequestMapped->setTransactionDesc("P2P");
        $this->transferRequestMapped->setMerchantId(123456);

        $this->transfer = $this->transferService->getTransfer($this->transferRequestMapped);
        // $this->assertTrue($this->transfer != null);
        // $this->assertTrue($this->transfer->getTransactionReference() > 0);
        // $this->assertTrue($this->transfer->getTransactionHistory() != null);
        // $this->assertTrue($this->transfer->getTransactionHistory()->getTransaction(0)->getResponse()->getCode() == 00);
        // $this->assertTrue($this->transfer->getTransactionHistory()->getTransaction(1)->getResponse()->getCode() == 00);
    }

    public function doPaymentRequestCardAccount() {
        $this->paymentRequestCard = new \PaymentRequest();
        $this->paymentRequestCard->setLocalDate("1212");
        $this->paymentRequestCard->setLocalTime("161222");
        $this->paymentRequestCard->setTransactionReference("4000000001111111112");
        $this->paymentRequestCard->setSenderName("John Doe");

        $address = new \SenderAddress();
        $address->setLine1("123 Main Street");
        $address->setLine2("#5A");
        $address->setCity("Arlington");
        $address->setCountrySubdivision("VA");
        $address->setPostalCode(22207);
        $address->setCountry("USA");
        $this->paymentRequestCard->setSenderAddress($address);

        $receivingCard = new \ReceivingCard();
        $receivingCard->setAccountNumber("5184680430000006");
        $this->paymentRequestCard->setReceivingCard($receivingCard);

        $receivingAmount = new \ReceivingAmount();
        $receivingAmount->setValue(182207);
        $receivingAmount->setCurrency(484);
        $this->paymentRequestCard->setReceivingAmount($receivingAmount);
        $this->paymentRequestCard->setICA("009674");
        $this->paymentRequestCard->setProcessorId("9000000442");
        $this->paymentRequestCard->setRoutingAndTransitNumber(990442082);

        $cardAcceptor = new \CardAcceptor();
        $cardAcceptor->setName("My Local Bank");
        $cardAcceptor->setCity("Saint Louis");
        $cardAcceptor->setState("MO");
        $cardAcceptor->setPostalCode(63101);
        $cardAcceptor->setCountry("USA");
        $this->paymentRequestCard->setCardAcceptor($cardAcceptor);
        $this->paymentRequestCard->setTransactionDesc("P2P");
        $this->paymentRequestCard->setMerchantId(123456);

        $this->transfer = $this->transferService->getTransfer($this->paymentRequestCard);
        // $this->assertTrue($this->transfer != null);
        // $this->assertTrue($this->transfer->getTransactionReference() > 0);
        // $this->assertTrue($this->transfer->getTransactionHistory() != null);
        // $this->assertTrue($this->transfer->getTransactionHistory()->getTransaction(0)->getResponse()->getCode() == 00);
    }

    public function doPaymentRequestMappedAccount() {
        $this->paymentRequestMapped = new \PaymentRequest();
        $this->paymentRequestMapped->setLocalDate("1212");
        $this->paymentRequestMapped->setLocalTime("161222");
        $this->paymentRequestMapped->setTransactionReference("4000000001111111113");
        $this->paymentRequestMapped->setSenderName("John Doe");

        $address = new \SenderAddress();
        $address->setLine1("123 Main Street");
        $address->setLine2("#5A");
        $address->setCity("Arlington");
        $address->setCountrySubdivision("VA");
        $address->setPostalCode(22207);
        $address->setCountry("USA");
        $this->paymentRequestMapped->setSenderAddress($address);

        $receivingMapped = new \ReceivingMapped();
        $receivingMapped->setSubscriberId("examplePHP@email.com");
        $receivingMapped->setSubscriberType("EMAIL_ADDRESS");
        $receivingMapped->setSubscriberAlias("My Debit Card");
        $this->paymentRequestMapped->setReceivingMapped($receivingMapped);

        $receivingAmount = new \ReceivingAmount();
        $receivingAmount->setValue(182207);
        $receivingAmount->setCurrency(484);
        $this->paymentRequestMapped->setReceivingAmount($receivingAmount);
        $this->paymentRequestMapped->setICA("009674");
        $this->paymentRequestMapped->setProcessorId("9000000442");
        $this->paymentRequestMapped->setRoutingAndTransitNumber(990442082);

        $cardAcceptor = new \CardAcceptor();
        $cardAcceptor->setName("My Local Bank");
        $cardAcceptor->setCity("Saint Louis");
        $cardAcceptor->setState("MO");
        $cardAcceptor->setPostalCode(63101);
        $cardAcceptor->setCountry("USA");
        $this->paymentRequestMapped->setCardAcceptor($cardAcceptor);
        $this->paymentRequestMapped->setTransactionDesc("P2P");
        $this->paymentRequestMapped->setMerchantId(123456);

        $this->transfer = $this->transferService->getTransfer($this->paymentRequestMapped);
        // $this->assertTrue($this->transfer != null);
        // $this->assertTrue($this->transfer->getTransactionReference() > 0);
        // $this->assertTrue($this->transfer->getTransactionHistory() != null);
        // $this->assertTrue($this->transfer->getTransactionHistory()->getTransaction(0)->getResponse()->getCode() == 00);
    }
}