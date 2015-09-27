<?php

namespace CashMoney\Data\Model;

class Trip implements \JsonSerializable {

	private $name;
	private $id;
	private $users;
	private $expenses;
	private $payments;

	public function __construct($id, $name, User $creator) {
		$this->id = $id;
		$this->name = $name;
		$this->users = array($creator);
		$this->expenses = array();
		$this->payments = array();
	}
	
	public function addExpense(Expense $expense) {
		$this->expenses[] = $expense;
	}
	
	public function addPayment(Payment $payment) {
		$this->payments[] = $payment;
	}
	
	public function addUser(User $user) {
		$this->users[] = $user;
	}
	
	public function removeUser(User $user) {
		$index = array_search($user, $this->users);
		array_splice($this->users, $index, 1);
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
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