<?php
namespace CoffeeCompany\Coffees;
use CoffeeCompany\Decorators;

class Capuccino extends \CoffeeCompany\Coffee {

	const COST = 350;
	private $ingredients = ["Coffee","Water","Milk","Whip"];

	public function getCost() {
		return self::COST;
	}

	public function getIngredients() {
		return $this->ingredients;
	}

}
?>