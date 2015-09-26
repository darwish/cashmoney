<?php

namespace CashMoney\Data\Model;

class Trip implements \JsonSerializable {

	private $users;
	private $expenses;
	private $payments;

	public function jsonSerialize() {
		return [
			'users' => $this->users,
			'expenses' => $this->expenses,
			'payments' => $this->payments,
		];
	}
}