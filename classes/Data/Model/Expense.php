<?php

namespace CashMoney\Data\Model;

class Expense implements \JsonSerializable {

	private $id;
	private $name;
	private $amount;

	private $paidBy;
	private $usedBy;

	public function jsonSerialize() {
		return [
			'id'     => $this->id,
			'name'   => $this->name,
			'amount' => $this->amount,
			'paidBy' => $this->paidBy,
			'usedBy' => $this->usedBy,
		];
	}

	public function getID() {
		return $this->id;
	}
	public function setID($id) {
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}

	public function getAmount() {
		return $this->amount;
	}
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	public function getPaidBy() {
		return $this->paidBy;
	}
	public function setPaidBy($paidBy) {
		$this->paidBy = $paidBy;
	}

	public function getUsedBy() {
		return $this->usedBy;
	}
	public function setUsedBy($usedBy) {
		$this->usedBy = $usedBy;
	}
}