<?php

namespace CoffeeCompany\Decorators;

use CoffeeCompany;

class CoffeeWithWhip extends CoffeeDecorator {

    const EXTRA_COST = 10;

    public function __construct(CoffeeCompany\Coffee $decoratedCoffee) {
        parent::__construct($decoratedCoffee);
    }

    public function getCost()
    {
        return parent::getCost() + self::EXTRA_COST;
    }

    public function getIngredients()
    {
			$ingredients = parent::getIngredients();
			array_push($ingredients, "Whip");
			return $ingredients;
    }
}
