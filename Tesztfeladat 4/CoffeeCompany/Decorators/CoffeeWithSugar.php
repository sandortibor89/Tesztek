<?php
/**
 * Created by PhpStorm.
 * User: gezamiklo
 * Date: 05/11/14
 * Time: 17:50
 */

namespace CoffeeCompany\Decorators;

use CoffeeCompany;

class CoffeeWithSugar extends CoffeeDecorator {

    const EXTRA_COST = 5;

    public function __construct(CoffeeCompany\Coffee $decoratedCoffee) {
        parent::__construct($decoratedCoffee);
    }

    public function getCost()
    {
        return parent::getCost() + self::EXTRA_COST;
    }

    public function getIngredients()
    {
        return parent::getIngredients() . ", Sugar";
    }
}
