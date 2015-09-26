<?php

namespace CashMoney\Data;

class Data {

	protected $dataFile;
	protected $data;

	public function __construct() {
		$this->dataFile = ROOT_DIR . 'data/db.txt';
		$this->load();
	}

	public function load() {
		if (!is_file($this->dataFile)) {
			$this->data = [];
			return;
		}

		$serializedData = file_get_contents($this->dataFile);
		$this->data = unserialize($serializedData);
	}

	public function save() {
		$serializedData = serialize($this->data);
		file_put_contents($this->dataFile, $serializedData);
	}

	public function getData() {
		return $this->data;
	}

	public function getExpenses() {
		return isset($this->data['expenses']) ? $this->data['expenses'] : [];
	}

	public function getPendingExpenses() {
		return array_values(array_filter($this->getExpenses(), function($e) {
			return $e->getIsPending();
		}));
	}

	public function getExpenseByID($expenseID) {
		$foundExpenses = array_values(array_filter($this->getExpenses(), function($expense) use($expenseID) {
			return $expense->getID() == $expenseID;
		}));
		return isset($foundExpenses[0]) ? $foundExpenses[0] : null;
	}

	public function addExpense(Model\Expense $expense) {
		$this->data['expenses'][] = $expense;
		$this->save();
	}

	public function removeExpense(Model\Expense $expense) {
		$this->data['expenses'] = array_values(array_filter($this->data['expenses'], function($e) use($expense) {
			return $e !== $expense;
		}));
		$this->save();
	}

	public function getUsers() {
		return isset($this->data['users']) ? $this->data['users'] : [];
	}

	public function getUsersByID(array $userIDs) {
		$foundUsers = array_filter($this->getUsers(), function($user) use($userIDs) {
			return in_array($user->getID(), $userIDs);
		});
		return array_values($foundUsers);
	}

	public function getUserByID($userID) {
		$foundUsers = array_values(array_filter($this->getUsers(), function($user) use($userID) {
			return $user->getID() == $userID;
		}));
		return isset($foundUsers[0]) ? $foundUsers[0] : null;
	}

	public function addUser(Model\User $user) {
		$this->data['users'][] = $user;
		$this->save();
	}

	public function removeUser(Model\User $user) {
		$this->data['users'] = array_values(array_filter($this->data['users'], function($e) use($user) {
			return $e !== $user;
		}));
		$this->save();
	}
}