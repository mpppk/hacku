<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script charset="UTF-8" src="http://js.api.olp.yahooapis.jp/OpenLocalPlatform/V1/jsapi?appid=dj0zaiZpPVphNFdrbTRqOHMzWSZzPWNvbnN1bWVyc2VjcmV0Jng9NmU-"></script>

<script>
window.onload=function(){
	var latlng = new Y.LatLng(34.2656931, 135.1515467);
	
	var map = new Y.Map("map");
	// 地図を表示
	map.drawMap(latlng, 15);

	// 経路探索レイヤを地図に重ねる
	var rlayer=new Y.RouteSearchLayer();
	map.addLayer(rlayer);

	map.bind("click", function(p){
		//alert("クリック位置:" + p.toString());
	});

	map.bind("moveend", function(){// 
		var center=map.getCenter();
		var cid="d115e2a62c8f28cb03a493dc407fa03f";
		var searchOption={
			"lat":center.lat(),
			"lon": center.lng(),
			"dist": 5,
			"sort":"dist"
		};
		var localsearch = new Y.LocalSearch();
		localsearch.search("",cid,searchOption,
			function(ydf){
				//alert("check");
				map.clearFeatures();
				map.addFeatures(ydf.features);
				// 検索実行
				var nearestFeature=ydf.features[0];
				rlayer.execute(
					[center,nearestFeature.getLatLng()],
					{"useCar":false}
				);
			}
		);
	});

	map.setConfigure('scrollWheelZooom', true);

	// マーカーを作成
	var marker= new Y.Marker(new Y.LatLng(34.27635037097195, 135.14708874013212));

	// 吹き出しの表示
	marker.bindInfoWindow("<u>和歌山大学前駅</u><br\>和歌山大学から歩いて２６分！");

	// 地図に追加
	map.addFeature(marker);

	map.addControl( new Y.LayerSetControl() );
	map.addControl(new Y.SliderZoomControlVertical());
	map.addControl(new Y.ScaleControl());
	map.addControl(new Y.CenterMarkControl());
};

</script>
</head>
<body>
	<div id="map" style="width:800px; height:500px;"></div>
</body>
</html>


