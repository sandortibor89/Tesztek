<?php
/**
 * Created by PhpStorm.
 * User: gezamiklo
 * Date: 05/11/14
 * Time: 15:21
 */

namespace CoffeeCompany\Decorators;

use CoffeeCompany;

abstract class CoffeeDecorator extends CoffeeCompany\Coffee
{
// Abstract decorator class - note that it extends Coffee abstract class
    protected $decoratedCoffee = "";
    protected $ingredientSeparator = ", ";

    public function __construct(CoffeeCompany\Coffee $decoratedCoffee)
    {
        $this->decoratedCoffee = $decoratedCoffee;
    }

    public function getCost()
    { // Implementing methods of the abstract class
        return $this->decoratedCoffee->getCost();
    }

    public function getIngredients()
    {
        return $this->decoratedCoffee->getIngredients();
    }

}
