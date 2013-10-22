<?php
require_once (dirname(__FILE__) . "/dbconfig.php");
require_once (dirname(__FILE__) . "/dbfuncs.php");
require_once (dirname(__FILE__) . "/functions.php");

// place1とplace2の距離を計算する
function calcDistance($place1Lat, $place1Lon, $place2Lat, $place2Lon) {
	return (double)sqrt( pow($place1Lat-$place2Lat, 2) + pow($place1Lon-$place2Lon, 2) );
}

$stamprallyID	= (int)$_POST['stamprallyID'];
$nowLat			= (double)$_POST['lat'];
$nowLon			= (double)$_POST['lon'];
$threshold		= (double)$_POST['threshold'];

$stamprally = new Stamprally($stamprallyID);
$srLon = (double)$stamprally->getColumnValue('lon');
$srLat = (double)$stamprally->getColumnValue('lat');

if( $srLon == null || $srLat == null ) {	// 座標が設定されていないとき
	echo "-1";
	
} else {
	$dist = (double)calcDistance($nowLat, $nowLon, $srLat, $srLon);
	
	if( $dist > $threshold ) {
		echo "0"; // 範囲外にいるとき
		
	} else {
		echo "1"; // 範囲内にいるとき
		
	}
}
