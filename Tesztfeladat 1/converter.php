<?php
$filename = "random.xml";

//fájl meglétének ellenörzése
if (file_exists($filename)) {
	//kirerjesztések kezelése
	switch(pathinfo($filename)['extension']) {
		case "xml":
		$xml = simplexml_load_file($filename);
		$json = json_encode($xml);
		$array = json_decode($json,true)['Product'];
		//ha csak egy eleme van akkor is tömb lesz
		$array = (array_key_exists('Id', $array)) ? [$array] : $array;
		break;

		case "csv":
		break;
	}
} else {
	echo "Nem létező fájl:".$filename;
	exit();
}

//html lista rajzolás
$ac = array_column($array, 'Id');
foreach ($array as $k => $v) {
	if (!empty($v['RelId'])) {
		$relnames = [];
		$relid = array_filter(is_array($v['RelId']) ? $v['RelId'] : [$v['RelId']]);
		foreach (array_intersect($ac, $relid) as $kk => $vv) {
			$relnames[] = $array[$kk]['Name'];
		}
	}
	echo '<ul>';
	echo '<li>'.$v['Id'].'</li>';
	echo '<li>'.$v['Name'].'</li>';
	if (!empty($relnames)) {
		echo '<li>Connected:<ul><li>';
		echo implode('</li><li>',$relnames);
		echo '</li></ul></li>';
	}
	echo '</ul><hr>';
}
//exit();
//lista vége


//csatlakozás stb.
$mysqli = new mysqli("localhost", "teszt", "teszt1234", "teszt");
if ($mysqli->connect_errno) {
	printf("Hiba: %s\n", $mysqli->connect_error);
	exit();
}

/*
Egyesével felviszem a tömbben lévő adatokat, visszatérő id egyeztetése után sikeres felvitelnek titulálom
A kapcsolatokat egyenlőre nem viszem fel, mert az adatbázis idegen kulcsai miatt hibát eredményezne ha
olyan id-t akar felvinni a program, amihez tartozó product még nem került be az adatbázisba.
Ezért kigyűjtöm egy tömbbe azokat a felvitt id-ket amelyekhez kapcsolatok tartoznak.

A program normális futása esetén az $inserted_ids tömbben vannak a kapcsolatok.
Viszont ha például valamelyik adatbázis felvitel közben valamiért leáll a program akkor gond van.
Előfordulhat hogy csak a product-ok fele ment fel, és akkor egyetlen kapcsolat sem jött létre,
az ezt tartalmazó tömb tartalma pedig elvész. Vagy épp a kapcsolatok felvitele közben adja meg magát valami,
akkor a kapcsolatok egy része nem kerül be az adatbázisba.
A sima újra futtatás nem lett volna megoldás hiszen az adatbázis duplikációk miatt
nem hozza létre a már fennt szereplő adatokat.

Ezért van egy $selected_ids tömb amibe akkor kerülnek adatok, ha az adatbázisba már létező product-ot akar
felvinni a program. Hiba esetén megkísérli lekérdezni az aktuális id-t, a hozzá tartozó kapcsolatokkal.
Ha van ilyen, össze veti az xml-ben lévő kapcsolatokkal, és ha van olyan ami az adatbázisban nem szerepel
akkor kerül a $selected_ids tömbbe a felvinni kívánt kapcsolat.
*/

$inserted_ids = [];
$selected_ids = [];

foreach ($array as $k => $v) {
	if (isSet($v['Id']) && isSet($v['Name'])) {
		$error = false;
		if (!empty($v['RelId'])) {
			$relid = array_filter(is_array($v['RelId']) ? $v['RelId'] : [$v['RelId']]);
			/*
			Erre azért van szükség mert az xml tömbbé alakítása során ha csak egy
			RelId szerepel egy product-ban, akkor sima string-ként jelenik meg, több elem esetén
			pedig tömb jön létre.
			*/
		} else {
			$relid = [];
		}
		if ($mysqli -> query('Insert Into `products` (id,name) VALUES('.$v['Id'].',"'.$v['Name'].'")')) {
			if ($mysqli -> insert_id == $v['Id']) {
				if (!empty($relid)) {
					$inserted_ids[$v['Id']] = $relid;
				}
			} else {
				$error = true;
			}
		} else {
			$error = true;
		}
		/*
		Ha a insert során hiba történik, itt próbálom lekérdezni az aktuális id-t
		hátha ez a hiba. A lekérdezés sikeressége esetén a kapcsolatokat is vissza adja.
		*/
		if ($error) {
			$sql = '
			Select `p`.`id`, `p`.`name`, Group_Concat(`pc`.`id_2` Separator ",") As `connects`
			From `products` `p`
			Left Join `products_connects` `pc`
			On `p`.`id` = `pc`.`id_1`
			Where `p`.`id` = '.$v['Id'].'
			Group By `p`.`id`
			Limit 1
			';
			if ($result = $mysqli -> query($sql)) {
				$row = $result -> fetch_assoc();
				if ($connects = explode(',', $row['connects'])) {
					$relid = array_diff($relid, $connects);
					if (!empty($relid)) {
						$selected_ids[$row['id']] = $relid;
					}
				}
			}
		}
	}
}

/*
Itt viszem fel a kapcsolatokat.
A kapcsolatokat már product id csoportokban viszem fel
*/
$errors = [];
$ids = array_filter(array_replace($inserted_ids, $selected_ids));
if (!empty($ids)) {
	foreach ($ids as $k => $v) {
		$values = '('.$k.','.implode('),('.$k.',',$v).')';
		$sql = 'Insert Into `products_connects` (id_1,id_2) VALUES'.$values;
		if (!$mysqli -> query($sql)) {
			$errors[$k] = $v;
		}
	}
}

$mysqli -> close();

if (empty($errors)) {
	print "Hell Yeah!";
} else {
	print "<pre>";
	print "Hibák:<br>";
	print_r($errors);
	print "</pre>";
}
?>