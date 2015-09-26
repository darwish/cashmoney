<?php

namespace CashMoney\Data\Model;

class Trip implements \JsonSerializable {

	private $name;
	private $id;
	private $users;
	private $expenses;
	private $payments;

	public function __contstruct($id, $name, Model\User $creator) {
		$this->id = $id;
		$this->name = $name;
		$this->users = array($creator);
	}
	
	public function jsonSerialize() {
		return [
			'name' 		=> $this->name,
			'id' 		=> $this->id,
			'users'	 	=> $this->users,
			'expenses' 	=> $this->expenses,
			'payments' 	=> $this->payments,
		];
	}
}