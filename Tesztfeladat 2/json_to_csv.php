<?php
$filename = "Riportokhoz adatok.json";

//fájl meglétének ellenörzése
if (!file_exists($filename)) { throw new Exception('File not found.'); }

//fájl betöltés, json validálás
$json = json_decode(file_get_contents($filename), true);

if($json === null) { throw new Exception('The json cannot be decoded'); }

$as = [
		'id' => 'Azonositoszam',
		'creationTime' => 'Regisztracio datuma',
		'email' => 'Regisztralt e-mail cime',
		'firstransaction' => 'Elso vasarlas datuma',
		'lastransaction' => 'Utolso vasarlas datuma',
		'name' => 'Nev',
		'phoneNumber' => 'Mobiltelefonszam',
		'status' => 'Statusz',
		'myPremiumFund' => 'Premium',
		'parent' => 'Ajanlo email cime',
		'myBTC' => 'Sajat BTC vasarlas erteke',
		'groutBTC' => 'Csoportos BTC forgalom',
		'FRH' => 'FRH',
		'BTH' => 'BTH',
		'CN' => 'CN',
		'HARD' => 'HARD',
		'CH' => 'CH',
		'CC' => 'CC'
];

$fp = fopen('file.csv', 'w');

foreach ($list as $fields) {
	fputcsv($fp, $fields);
}
fclose($fp);

/*
A feladatot nem fejeztem be, úgy gondoltam a hiányosságok miatt inkább felvázolom hogy oldanám meg.
Létrehoztam egy $as nevű tömböt, ami aliasokat tartalmaz, egészen pontosan a csv leendő oszlopainak
kulcsát, és a hozzá tartozó neveket (a tömb nem teljes). A terv az volt hogy a JSON-t ugye tömb-ként
megkaptam (ha sikeres a validálás). Az egyértelmű adatokat asszociatív tömbbe rendeztem volna,
olyan formában hogy az fputcsv() funkció fájlba tudja írni soronként a foreach segítségével.
A json-ban lévő adatokat a Users tömb végigjárásával (foreach) szereztem volna be. Az egyértelmű
adatokat mint pl. id, név, email stb. az gondolkodás nélkül mehet a kiírni szánt tömbbe.
Ami átalakításra szorul:
Dátumok - értelem szerűen a timestap-ek normális dátummá alakítás után mehetnek a tömbbe
Az első és utolsó vásárlás dátumát és a tranzakciók rész végigjárásával állapitanám meg, hiz azokban
vannak dátumok.
Az utolsó meghívott regisztráció dátum - a meghívottak id-i gondolom az invited része a felhasználó adatainak.
ezek alapján be lehet azonosítani a meghívottakat, és kiválasztani dátum alapján a megfelelőt.
Ugyanígy a meghívottak száma az invited tömb elemeinek száma.
Illetve vannak mellettük true értékek, gondolom ez a sikeres meghívást vagy regisztrációt jelzi,
Ez alapján ki lehet szűrni akik valami okból nem regisztráltak.

Ha az adatok feldolgozása megvan, ami egyébként néhány php tömb fügvénnyel megoldható, azután az fputcsv()
szépen kiírja fájlba.

Annyit még hozzá fűznék hogy a kiírandó asszociatív tömb "sorait" mindig fix mérettel hoznám létre,
és a rendelkezésre álló mezőket kitölteném, a többi üres marad. Így a csv fájl is fix sorokkal jön létre,
az üres adatoknak is ott lesz a helyük a megfelelő feldolgozás érdekében.
*/
?>