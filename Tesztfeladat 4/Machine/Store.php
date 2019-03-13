<?php
namespace Machine;
use System;

final class Store extends System {

	const JSON = ROOT_DIR.DS.'Machine'.DS.'Store.json';
	private $data = [];

	protected function __construct() {}

	public function check($ingredients) {
		$store = $this->getJson();
		foreach ($ingredients as $k => $v) {
			if ($store[$k] < $v) { throw new \Exception("Empty ".$k); }
		}
	}

	private function getJson():array {
		if (empty($this->data)) {
			return file_exists(self::JSON) ? (json_decode(file_get_contents(self::JSON), true) ?? []) : [];
		} else {
			return $this->data;
		}
	}

}
?>