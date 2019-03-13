<?php
namespace Machine;
use System;

class Coffee extends System {

	public function minCost() {
		$cost = null;
		foreach ($this->getAll() as $k => $v) {
			$class = 'Machine_Coffees_'.$v;
			$ccost = System::$class()::COST;
			if (is_null($cost) || $cost > $ccost) {
				$cost = $ccost;
			}
		}
		return $cost ?? [];
	}

	public function getAll():array {
		$coffees = array_diff(scandir(ROOT_DIR.DS.'Machine'.DS.'Coffees'), ['.', '..']);
		$walk_function = function (&$item) {
			$explode = explode('.', $item);
			$coffee = reset($explode);
			$class = 'Machine_Coffees_'.$coffee;
			if (count($explode) === 2 && end($explode) === 'php' && System::$class()->checkIngredients()) {
				$item = $coffee;
			} else {
				$item = null;
			}
		};
		array_walk($coffees, $walk_function);
		$coffees = array_filter($coffees);
		return empty($coffees) ? [] : array_combine(range(1, count($coffees)), $coffees);
	}

	protected function checkIngredients():bool {
		$class = str_replace('\\', '_', get_called_class());
		try {
			System::Machine_Store()->check(System::$class()::INGREDIENTS);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function select($name) {
		$class = 'Machine_Coffees_'.$name;
		System::Machine_ShoppingCart()->minMoney(System::$class()::COST);
		cl();
		echo "Balance: ".System::Machine_ShoppingCart()->money." Fabatkas\n\n";
		echo "1x ".System::$class();
		//ez itt egy példa hogy kb így fog kinézni a végeredmény
		echo "2x +Milk 60\n";
		echo "3x +Sugar 15\n";
		echo "Total: 475\n\n";
		//ide jön még egy do while a hozzávalókkal, majd kosárba rakás
	}

	public function __toString() {
		$class = str_replace('\\', '_', get_called_class());
		return System::$class()::NAME.' ('.implode(', ',array_keys(System::$class()::INGREDIENTS)).') '.System::$class()::COST."\n";
	}

}
?>