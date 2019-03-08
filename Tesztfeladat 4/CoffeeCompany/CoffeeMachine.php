<?php
/**
 * Created by PhpStorm.
 * User: gezamiklo
 * Date: 05/11/14
 * Time: 15:29
 */

namespace CoffeeCompany;

use CoffeeCompany\Decorators;

class CoffeeMachine {

    protected $coffe = null;
    protected $money = 0;

    public function __construct()
    {
        echo "Welcome to CoffeeMachine!\n\n";
        // Ezt ird at, hogy egy espresso ara legyen a minimum
        echo "Please give me at least 300 Fabatkas\n\n";
    }

    public function pay($money)
    {
        $this->money += (int)$money;
        echo "Your new balance is: " . $this->money."\n";
    }

    public function getCoffee()
    {
        $this->coffe = new Espresso();
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
    protected function getEstimatedBalance()
    {
        return $this->money - $this->coffe->getCost();
    }

    protected function printEstimatedBalance()
    {
        echo "You have " . $this->getEstimatedBalance() . " Fabatka(s) left\n";
    }

    protected function printIngredients()
    {
        echo "Your coffee's ingredients are: " . $this->coffe->getIngredients() . "\n";
    }

}