<?php
/**
 * Created by PhpStorm.
 * User: gezamiklo
 * Date: 05/11/14
 * Time: 15:26
 */

namespace CoffeeCompany\Decorators;

use CoffeeCompany;

class CoffeeWithMilk extends CoffeeDecorator{

    const EXTRA_COST = 30;

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
			array_push($ingredients, "Milk");
      return $ingredients;
    }
}