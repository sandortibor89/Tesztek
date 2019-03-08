<?php
/**
 * Created by PhpStorm.
 * User: gezamiklo
 * Date: 05/11/14
 * Time: 15:18
 */
namespace CoffeeCompany;

abstract class Coffee {
    public abstract function getCost();
    public abstract function getIngredients();
} 