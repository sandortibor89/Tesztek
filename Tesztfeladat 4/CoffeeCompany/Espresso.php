<?php
/**
 * Created by PhpStorm.
 * User: gezamiklo
 * Date: 05/11/14
 * Time: 15:19
 */
namespace CoffeeCompany;

use CoffeeCompany\Decorators;

class Espresso extends Coffee {
// Extension of a simple coffee without any extra ingredients

    const COST = 300;

    public function getCost()
    {
        return self::COST;
    }

    public function getIngredients()
    {
        return "Coffee, Water";
    }

}