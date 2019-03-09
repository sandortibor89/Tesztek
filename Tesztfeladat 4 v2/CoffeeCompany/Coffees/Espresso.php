<?php
/**
* Created by PhpStorm.
* User: gezamiklo
* Date: 05/11/14
* Time: 15:19
*/
namespace CoffeeCompany\Coffees;

use CoffeeCompany\Decorators;

class Espresso extends \CoffeeCompany\Coffee {
	// Extension of a simple coffee without any extra ingredients
	const COST = 300;
	private $ingredients = ["Coffee","Water"];

	public function getCost() {
		return self::COST;
	}

	public function getIngredients() {
		return $this->ingredients;
	}

}