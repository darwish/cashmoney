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

	public function getExpenses() {
		return isset($this->data['expenses']) ? $this->data['expenses'] : [];
	}

	public function getExpenseByID($expenseID) {
		$foundExpenses = array_filter($this->getExpenses(), function($expense) use($expenseID) {
			return $expense->getID() == $expenseID;
		});
		return array_values($foundExpenses)[0];
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
}