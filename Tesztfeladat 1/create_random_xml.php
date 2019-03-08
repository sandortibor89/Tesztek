<?php

$array = [];

//random "szavak"
function getRandomWord() {
	$len = rand(5, 20);
	$word = array_merge(range('a', 'z'), range('A', 'Z'));
	shuffle($word);
	return substr(implode($word), 0, $len);
}

//tömb létrehozás
for ($i=0; $i < 8000; $i++) {
	while (1) {
		$int = rand(1000,9999);
		if (!in_array($int,array_column($array, 'Id'))) { break; }
	}
	$array[] = ['Id' => $int, 'Name' => getRandomWord()];
}

//számlálók
$ids = array_column($array, 'Id');
$n = count($ids)-1;

//random kapcsolatok léterhozása
foreach ($array as $k => $v) {
	$connects = [];
	for ($j=0; $j < rand(0,10); $j++) {
		while (1) {
			$randomkey = rand(0, $n);
			if (!in_array($ids[$randomkey],$connects) && $ids[$randomkey] != $v['Id']) {
				$connects[] = $ids[$randomkey];
				break;
			}
		}
	}
	if (!empty($connects)) {
		$array[$k]['RelId'] = $connects;
	}
}

$array = ['Product' => $array];

//xml generálása rekurzívan
function createXml(SimpleXMLElement $object, array $data) {
	foreach($data as $key => $value) {
		if (is_array($value)) {
			foreach ($value as $kk => $vv) {
				if (is_array($vv)) {
					$new_object = $object->addChild($key);
					createXml($new_object, $vv);
				} else {
					$object->addChild($key, $vv);
				}
			}
		} else {
			$object->addChild($key, $value);
		}
	}
}

$xml = new SimpleXMLElement('<Products/>');
createXml($xml, $array);

//xml kiírás
header("Content-type: text/xml");
print $xml->asXML();
?>