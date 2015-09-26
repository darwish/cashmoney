<?php

namespace CashMoney;

class Auth {
	private $id = 1; // Hardcoded to darwish!

	public function getCurrentUser() {
		$data = new Data\Data();
		return $data->getUserByID($this->id);

		if (!$user) {
			throw new Exception("Cannot find current user for id: {$this->id}");
		}

		return $user;
	}
}