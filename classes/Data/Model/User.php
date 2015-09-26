<?php

namespace CashMoney\Data\Model;

class User implements \JsonSerializable {

	private $expenses;
	private $payments;

	public function jsonSerialize() {
		return [
			'expenses' => $this->expenses,
			'payments' => $this->payments,
		];
	}
}