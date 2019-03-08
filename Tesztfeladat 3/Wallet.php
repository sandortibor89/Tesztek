<?php

//A lentebb található Wallet osztály példányosítsása csak az ősosztályon keresztül lehetséges: System::wallet();
//Mindez azért mert nem adatbázisból dolgozik, így a futás folyamán megmaradnak a tárca adatai egy esetleges újra példányosítás esetén

abstract class System {

	private static $map = [];

	private function __construct() {}

	private static function getInstance($class,$args):self {
		if (!isset(self::$map[$class])) {
			if(count($args) === 0) {
				self::$map[$class] = new $class();
			} else {
				$rc = new \ReflectionClass($class);
				self::$map[$class] = $rc->newInstanceArgs($args);
			}
		}
		return self::$map[$class];
	}

	public static function __callStatic($name, $arguments = []):self {
		return self::getInstance(ucfirst($name),$arguments);
	}
}

class Wallet extends System {

	private $wallet = [5,10,20,50,100,200,500,1000,2000,5000,10000,20000];

	protected function __construct() {
		//a tömb kulcsai maguk a címletek, az értékük pedig a számláló
		$this -> wallet = array_fill_keys($this -> wallet, 0);
	}

	public function add($money = 0):bool {
		//ha nem tömb a bemenet, akkor az lesz
		$money = is_array($money) ? $money : [$money];

		//itt derül ki hogy a tömb minden eleme szabványos címlet
		if (empty(array_diff($money, array_keys($this -> wallet)))) {
			//tárca feltöltése
			foreach (array_count_values($money) as $key => $value) {
				$this -> wallet[$key]+= $value;
			}
			return true;
		} else {
			throw new InvalidArgumentException();
		}
	}

	public function getContent():stdClass {
		$money = new stdClass();
		$money -> change = [];
		$money -> paper = [];
		foreach (array_filter($this -> wallet) as $key => $value) {
			if ($key < 500) {
				for ($i=0; $i < $value; $i++) {
					array_push($money -> change,$key);
				}
			} else {
				for ($i=0; $i < $value; $i++) {
					array_push($money -> paper,$key);
				}
			}
		}

		return $money;
	}

	public function sum():int {
		$money = $this -> getContent();
		return array_sum($money -> change) + array_sum($money -> paper);
	}

	public function takeOut(stdClass $money):bool {
		$money = array_merge($money -> change, $money -> paper);
		if (empty(array_diff($money, array_keys($this -> wallet)))) {
			$check = true;
			foreach ($money = array_count_values($money) as $key => $value) {
				if ($this -> wallet[$key] < $value) {
					$check = false;
					break;
				}
				$money[$key] = $this -> wallet[$key] - $value;
			}
			if ($check) {
				$this -> wallet = array_replace($this -> wallet,$money);
			}
			return $check;
		} else {
			return fasle;
		}
	}

	public function removable(int $money) {
		if ($money > 0 && $money%5 == 0) {
			if ($this -> sum() >= $money) {
				$i = 0;
				foreach (array_filter(array_reverse($this -> wallet, true)) as $key => $value) {
					for ($j=0; $j < $value; $j++) {
						if (($i+$key) <= $money) { $i+= $key; }
					}
				}
				if ($i != $money) {
					throw new InvalidArgumentException();
				} else {
					return true;
				}
			} else {
				throw new Exception("Too much");
			}
		} else {
			throw new InvalidArgumentException();
		}
	}
}
?>