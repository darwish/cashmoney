<?php

namespace CashMoney\Data\Model;

class Payment implements \JsonSerializable {

	private $debtor;
	private $lender;
	private $amount;
	private $isPaid = false;

	public function __construct(User $debtor, User $lender, $amount) {
		$this->debtor = $debtor;
		$this->lender = $lender;
		$this->amount = $amount;
	}

	public function jsonSerialize() {
		return [
			'debtor' => $this->debtor,
			'lender' => $this->lender,
			'amount' => $this->amount,
			'isPaid' => $this->isPaid,
		];
	}

	public function getDebtor() {
		return $this->debtor;
	}
	public function setDebtor(User $debtor) {
		$this->debtor = $debtor;
	}

	public function getLender() {
		return $this->lender;
	}
	public function setLender(User $lender) {
		$this->lender = $lender;
	}

	public function getAmount() {
		return $this->amount;
	}
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	public function getIsPaid() {
		return $this->isPaid;
	}
	public function setIsPaid($isPaid) {
		$this->isPaid = $isPaid;
	}
}