<?php
namespace Machine\Coffees;
use Machine\Coffee;

class Espresso extends Coffee {

	const NAME = 'Espresso';
	const COST = 300;
	const INGREDIENTS = [
		'Coffee' => 1,
		'Water' => 1
	];

}
?>