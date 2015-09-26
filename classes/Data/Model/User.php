<?php

namespace CashMoney\Data\Model;

class User implements \JsonSerializable {

	private $id;
	private $name;

	private $expenses = [];
	private $payments = [];

	public function jsonSerialize() {
		return [
			'id'       => $this->id,
			'name'     => $this->name,
			'expenses' => $this->expenses,
			'payments' => $this->payments,
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
}