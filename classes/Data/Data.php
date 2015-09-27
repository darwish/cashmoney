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

	public function addTrip(Model\Trip $trip) {
		// Prepend so that more recent ones come up first.
		$this->data['trips'] = isset($this->data['trips']) ? $this->data['trips'] : [];
		array_unshift($this->data['trips'], $trip);
		$this->save();
	}

	public function getTrips() {
		$trips = array_values(array_filter($this->data['trips'], function($trip) {
			return !empty($trip->getID());
		}));
		$this->data['trips'] = $trips;
		$this->save();
		
		return isset($this->data['trips']) ? $this->data['trips'] : [];
	}

	public function getTrip($id) {
		$trips = array_values(array_filter($this->getTrips(), function($trip) use($id) {
			return $trip->getID() == $id;
		}));
		return isset($trips[0]) ? $trips[0] : null;
	}

	public function removeTrip(Model\Trip $trip) {
		$this->data['trips'] = array_values(array_filter($this->data['trips'], function($x) use($trip) {
			return $x !== $trip;
		}));
		$this->save();
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

	public function splitExpenses(array $expenses) {
		$sharedExpenses = array_values(array_filter($expenses, function($e) {
			return count($e->getUsedBy()) > 0;
		}));

		$formattedExpenses = array_map(function($e) {
			$usedByIDs = array_map(function($u) {
				return $u->getID();
			}, $e->getUsedBy());

			return [$e->getName(), $e->getAmount(), $e->getPaidBy()->getID(), $usedByIDs];
		}, $sharedExpenses);

		$formattedExpensesString = json_encode($formattedExpenses);

		$encodedString = bin2hex($formattedExpensesString);

		// if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		// 	$escapedExpensesString = preg_replace('/"/', '""', $formattedExpensesString);
		// } else {
		// 	$escapedExpensesString = escapeshellcmd($formattedExpensesString);
		// }

		$cmd = "python splitengine.py \"$encodedString\" 2>&1";
		exec($cmd, $output, $return_value);

		$splits = json_decode($output[0], true);

		$payments = [];
		foreach ($splits as $split) {
			$debtor = $this->getUserByID($split[0]);
			$lender = $this->getUserByID($split[1]);
			$amount = $split[2];

			$payments[] = new Model\Payment($debtor, $lender, $amount);
		}

		return $payments;
	}

	public function getPayment($tripID, $debtorID, $lenderID) {
		$trip = $this->getTrip($tripID);
		$payments = $trip->getPayments();

		foreach ($payments as $payment) {
			$isCorrectDebtor = $payment->getDebtor()->getID() == $debtorID;
			$isCorrectLender = $payment->getLender()->getID() == $lenderID;
			if ($isCorrectDebtor && $isCorrectLender) {
				return $payment;
			}
		}

		throw new \Exception("Could not find payment for trip '$trip' defined by debtor '$debtor' and lender '$lender'");
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
