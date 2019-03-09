<?php
/**
* Created by PhpStorm.
* User: gezamiklo
* Date: 05/11/14
* Time: 15:29
*/

namespace CoffeeCompany;

class CoffeeMachine {

	protected $coffe = null;
	protected $money = 0;

	public function __construct() {
		$minimum = Coffees\Espresso::COST;
		echo "Welcome to CoffeeMachine!\n\n";
		echo "Please give me at least $minimum Fabatkas\n\n";
		$this->getMoney($minimum);
		$this->selectCoffee();
	}

	private function getMoney(int $min_money):bool {
		while ($this->money < $min_money) {
			$input = readline('Need '.($min_money-$this->money).' Fabatka(s): ');
			//egy rejtett funkció, a pénzkérést egy x-el meg lehet szakítani
			if ($input === "x") { return false; }
			$this->pay($input);
		}
		return true;
	}

	public function pay($money) {
		$this->money += (int)$money;
		echo "Your new balance is: $this->money\n";
	}

	private function getCoffees():array {
		$coffees = array_diff(scandir(dirname(__FILE__).DIRECTORY_SEPARATOR."Coffees"), ['.', '..']);
		$walk_function = function(&$item) {
			$explode = explode('.', $item);
			if (count($explode) === 2 && end($explode) === 'php') {
				$item = reset($explode);
			} else {
				$item = null;
			}
		};
		array_walk($coffees, $walk_function);
		return array_combine(range(1, count($coffees)), array_values($coffees)) ?? [];
	}

	public function selectCoffee() {
		cls();
		echo "Your balance is: $this->money Fabatkas\n\n";

		$coffees = $this->getCoffees();

		echo implode("\n", array_map(
			function($v, $k) {
				$class = "CoffeeCompany\Coffees\\$v";
				return sprintf("%s - %s ".$class::COST." Fabatkas", $k, $v);
			},
			$coffees,
			array_keys($coffees)
		));
		echo "\nx - exit";

		$input = readline("\nPlease choose: ");

		if (in_array($input, array_keys($coffees))) {
			$class = "CoffeeCompany\Coffees\\$coffees[$input]";
			$this->coffe = new $class();
		} elseif ($input === "x") {
			exit;
		} else {
			$this->selectCoffee();
		}
		cls();
		$this->printEstimatedBalance();
		$this->printIngredients();
	}

	public function addMilk()
	{
		$this->coffe = new Decorators\CoffeeWithMilk($this->coffe);
		$this->printEstimatedBalance();
		$this->printIngredients();
	}

	public function addSugar()
	{
		$this->coffe = new Decorators\CoffeeWithSugar($this->coffe);
		$this->printEstimatedBalance();
		$this->printIngredients();
	}

	public function addWhip()
	{
		$this->coffe = new Decorators\CoffeeWithWhip($this->coffe);
		$this->printEstimatedBalance();
		$this->printIngredients();
	}

	protected function getEstimatedBalance()
	{
		if ($this->money < $this->coffe->getCost()) {
			$this->getMoney($this->coffe->getCost());
		}
		return $this->money - $this->coffe->getCost();
	}

	protected function printEstimatedBalance()
	{
		echo "You have " . $this->getEstimatedBalance() . " Fabatka(s) left\n";
	}

	protected function printIngredients()
	{
		echo "Your coffee's ingredients are: " . $this->coffe . "\n";
	}

}