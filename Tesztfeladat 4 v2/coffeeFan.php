<?php
/**
* Ez csak egy kis extra, néhol törli a képernyőt.
* CLS false esetén nem törli a képernyőt
* Windows esetén ha jól emlékszem a "clear" helyett "cls" kell
*/
define("CLS", "clear");
function cls() { if (CLS !== false) system(CLS); }

cls();
require './CoffeeCompany/autoload.php';

$coffeeMachine = new \CoffeeCompany\CoffeeMachine();

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
