<?php
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

function getCoordinates($address) {
   	// yahooジオコーダを用いて、住所から緯度経度を取得
	$geoURL = "http://geo.search.olp.yahooapis.jp/OpenLocalPlatform/V1/geoCoder?appid=". APP_ID. "&query=". $address;
	$xml = (array)simplexml_load_file($geoURL);
	$tempCoordinates = split(",", $xml["Feature"]->Geometry->Coordinates);
	$coordinates["lon"] = $tempCoordinates[0];
	$coordinates["lat"] = $tempCoordinates[1];
	return $coordinates;
}

?>

