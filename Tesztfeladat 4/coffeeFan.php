<?php
require './CoffeeCompany/autoload.php';

$coffeeMachine = new \CoffeeCompany\CoffeeMachine();
$coffeeMachine->pay(400);

$coffeeMachine->getCoffee();

do {
    $input = readline('Please choose (s - sugar, m - milk, w - whip, x - exit): ');
    switch ($input) {
        case "s":
            $coffeeMachine->addSugar();
            break;
        case "m":
            $coffeeMachine->addMilk();
            break;
        case "w":
            $coffeeMachine->addWhip();
            break;
    }
} while ($input != "x");
