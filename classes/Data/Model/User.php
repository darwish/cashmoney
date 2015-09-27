<?php

namespace CashMoney\Data\Model;

class User implements \JsonSerializable {

	private $id;
	private $name;
	private $photofile;

	private $expenses = [];
	private $payments = [];

	public function jsonSerialize() {
		return [
			'id'       => $this->id,
			'name'     => $this->name,
			'expenses' => $this->expenses,
			'payments' => $this->payments,
			'photo'	   => $this->photofile,
		];
	}

	public function getID() {
		return $this->id;
	}
	public function setID($id) {
		$this->id = $id;
	}
	
	public function setPhoto($photo) {
		$this->photofile = $photo;
	}
	
	public function getPhoto() {
		return photofile;
	}

	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
	}
}