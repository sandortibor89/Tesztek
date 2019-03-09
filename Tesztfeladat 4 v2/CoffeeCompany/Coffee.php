<?php
namespace CoffeeCompany;

abstract class Coffee {

	public function __toString(){
		$input = array_count_values($this->getIngredients());
		$output = implode(', ', array_map(
			function($v, $k) { return sprintf("%s x%s", $k, $v); },
			$input,
			array_keys($input)
		));
		return $output;
	}
}
?>