<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	// if (!isset($_SESSION['me']) && !isset($_GET['dbg'])) {
	//     echo "(in checkCheckpointProcess.php)user session timeout.<br>";exit();
	// }
	$_SESSION['beforeURL'] = "http://www.creagp.com/hacku/checkCheckpointProcess.php". "?id=". $_GET['id'];

	require_once(dirname(__FILE__) . "/php/allRequire.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyRally</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
   	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
</head>
<body>
</body>
<script>
	//ユーザーの現在の位置情報を取得
	navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

	/***** ユーザーの現在の位置情報を取得 *****/
	function successCallback(position) {
		var currentPostionLat = position.coords.latitude;
		var currentPostionLon = position.coords.longitude;
		var threshold = 10;
		$.post('php/_check_inside.php', {
		    stamprallyID: <?php echo h($_GET['id']); ?>,
		    lat: currentPostionLat,
		    lon: currentPostionLon,
		    threshold: threshold
		}, function(rs) {
			if(rs == "1" || rs == "-1"){// 成功時orスタンプラリーの緯度経度が登録されていない時
				<?php 
				$_SESSION["currentCheckpoint"] = $_GET['id']; ?>
				// location.href = 'checkCheckpoint.php';
				location.href = 'checkCheckpoint.php';
			}
			if(rs == "0"){// 失敗時
				alert("GPSをONにしてください!: " + rs);
			}
		});
	}

	/***** 位置情報が取得できない場合 *****/
	function errorCallback(error) {
		var err_msg = "";
		switch(error.code){
			case 1:
				err_msg = "位置情報の利用が許可されていません";
				break;
			case 2:
				err_msg = "デバイスの位置が判定できません";
				break;
			case 3:
				err_msg = "タイムアウトしました";
				break;
		}
		document.getElementById("show_result").innerHTML = err_msg;
		//デバッグ用→　document.getElementById("show_result").innerHTML = error.message;
	}
</script>


</html>


