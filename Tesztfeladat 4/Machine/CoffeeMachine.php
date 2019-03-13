<?php
namespace Machine;
use System;

final class CoffeeMachine extends System {

	protected function __construct() {
		cl();
		echo "Welcome to CoffeeMachine!\n\n";
		$min = System::Machine_Coffee()->minCost();
		if (empty($min)) { die("Machine is empty. Sorry.\n\n"); }
		echo "Please give me at least $min Fabatkas\n\n";
		System::Machine_ShoppingCart()->minMoney($min);
		$this->selectCoffee();
	}

	private function selectCoffee() {
		cl();
		echo "Balance: ".System::Machine_ShoppingCart()->money." Fabatkas\n\n";
		$coffees = System::Machine_Coffee()->getAll();
		echo implode("\n", array_map(
			function ($v, $k) {
				$class = 'Machine_Coffees_'.$v;
				return sprintf("%s - %s", $k, System::$class()::NAME.' /'.System::$class()::COST.' Fabatkas/');
			},
			$coffees,
			array_keys($coffees)
		));
		echo "\nx - Exit and money return";
		$input = readline("\nPlease choose: ");
		if (in_array($input, array_keys($coffees))) {
			$class = 'Machine_Coffee';
			System::$class()->select($coffees[$input]);
		} elseif ($input === 'x') {
			System::Machine_ShoppingCart()->moneyReturn();
			$this->__construct();
		} else {
			$this->selectCoffee();
		}
	}

	

}
?>