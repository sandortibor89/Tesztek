<?php
namespace Machine\Coffees;
use Machine\Coffee;

class Capuccino extends Coffee {

	const NAME = 'Capuccino';
	const COST = 350;
	const INGREDIENTS = [
		'Coffee' => 1,
		'Water' => 1,
		'Milk' => 1,
		'Whip' => 1
	];

}
?>