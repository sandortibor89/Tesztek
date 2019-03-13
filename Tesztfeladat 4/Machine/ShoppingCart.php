<?php
namespace Machine;
use System;

class ShoppingCart extends System {

	private $money = 0;

	public function __get($name) {
		return $this->$name;
	}

	public function minMoney(int $money) {
		while($this->money < $money) {
			$input = readline('Need '.($money-$this->money).' Fabatkas: ');
			$this->pay($input);
		}
	}

	private function pay($money) {
		$this->money += (int)$money;
		echo "Your new balance is: $this->money\n";
	}

	public function moneyReturn() {
		$this->money = 0;
	}

}
?>