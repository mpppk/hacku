<?php
	require_once(dirname(__FILE__) . '/php/UltimateOAuth.php');
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	// if (!isset($_SESSION['me']) && !isset($_GET['dbg'])) {
	//     echo "(in checkCheckpointProcess.php)user session timeout.<br>";exit();
	// }
	$_SESSION['targetURL'] = "http://www6063ue.sakura.ne.jp/hacku/checkCheckpointProcess.php". "?id=". $_GET['id'];

	require_once(dirname(__FILE__) . "/php/allRequire.php");
	// echo "session_id: ". session_id(). "<br>";

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
	チェックポイントにとばすで
</body>
<script>
	//ユーザーの現在の位置情報を取得
	navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

	/***** ユーザーの現在の位置情報を取得 *****/
	function successCallback(position) {
		var currentPostionLat = position.coords.latitude;
		var currentPostionLon = position.coords.longitude;
		var threshold = 0.01;
		$.post('php/_check_inside.php', {
		    stamprallyID: <?php echo h($_GET['id']); ?>,
		    lat: currentPostionLat,
		    lon: currentPostionLon,
		    threshold: threshold
		}, function(rs) {
			if(rs == "1" || rs == "-1"){// 成功時orスタンプラリーの緯度経度が登録されていない時
				alert("response of check inside: " + rs);
				<?php 
				$_SESSION["currentCheckpoint"] = $_GET['id'];
				$_SESSION[""] ?>
				// location.href = 'checkCheckpoint.php';
				location.href = 'checkCheckpoint.php';
			}
			if(rs == "0"){// 失敗時
				// location.href = 'checkCheckpoint.php';
				alert("チェックポイントと現在地が遠すぎます！" + rs);
				location.href = 'index.php';
			}
		});
	}

	/***** 位置情報が取得できない場合 *****/
	function errorCallback(error) {
		var err_msg = "位置情報取得失敗したで";
		switch(error.code){
			case 1:
				alert("位置情報の利用が許可されていませんがデモなので無視しマース");
				err_msg = "位置情報の利用が許可されていません";
				break;
			case 2:
				alert("デバイスの位置が判定できませんがデモなので無視しマース");
				err_msg = "デバイスの位置が判定できません";
				break;
			case 3:
				alert("タイムアウトしたけどデモなので無視しマース");
				err_msg = "タイムアウトしました";
				break;
		}
		location.href = 'checkCheckpoint.php';
		document.getElementById("show_result").innerHTML = err_msg;
		//デバッグ用→　document.getElementById("show_result").innerHTML = error.message;
	}
</script>


</html>


