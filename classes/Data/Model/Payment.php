<?php

namespace CashMoney\Data\Model;

class Payment implements \JsonSerializable {

	private $debtor;
	private $lender;
	private $amount;

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
		];
	}
}