<?php
	require_once("php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	// デバッグ用の定義
	define("tempTwID", 127982310);

	// 
	$startDate = $_POST["startDay"]. " ". $_POST["startTime"]. ":00";
	$endDate = $_POST["endDay"]. " ". $_POST["endTime"]. ":00";
	//var_dump($startDate);
	//var_dump($endDate);
	
	// 住所から緯度経度を取得
	$coordinates = getCoordinates($_POST["place"]);
	//var_dump($coordinates);

	$addedStamprallyID = StampRally::add($_POST["stamprallyName"],
		tempTwID,
		$_POST["place"],
		$_POST["description"],
		$startDate,
		$endDate);


	$addedStamprally = new StampRally($addedStamprallyID);
	echo $addedStamprally->update(
		null,
		null,
		$_POST["masterName"],
		null,
		$coordinates["lat"],
		$coordinates["lon"],
		null,
		null,
		null);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyStamp!!</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/hacku.css">

</head>
<body>
<?php include (HEADER_NAME); ?>
<?php include (MENU_NAME); ?>
<div id="main">
	<div id="contents">
		<h1>新しいスタンプラリーを登録しました!</h1>
 		<p>スタンプラリー名：<?php echo $_POST["stamprallyName"]; ?></p>
		<p>場所：<?php echo $_POST["place"]; ?></p>
		<p>説明:<?php echo $_POST["description"]; ?></p>
		<p>開始日時:<?php echo $startDate; ?></p>
		<p>終了日時:<?php echo $endDate; ?></p>
		
		<h1>チェックポイントを登録してください（仮）</h1>
		<form action="addCheckpointProcess.php" method="post">
		<input type="hidden" name="stamprallyID" value="<?php echo $addedStamprallyID; ?>">
		<div id="points">
			<div class="point" id="point0" data-id="0">
				<p>チェックポイント名: <input type="text" name="pointName0" value="tempPointName"></p>
				<p>概要説明: <input type="text" name="publicDescription0" value="tempDescription"></p>
				<p>詳細説明: <input type="text" name="privateDescription0" value="tempDescription"></p>
				<p><input type="button" class="removePoint" value="削除"></p>
				<p>--------------------------------------------------------------------------------</p>
			</div>
		</div>
		<p><input type="button" id="addPoint" value="チェックポイント追加"></p>
		<input type="submit" />
		</form>
		
	</div>
</div>
<?php include (FOOTER_NAME); ?>
</body>
<script>
$(document).on('click', '#addPoint', function() {
	var id = $('.point').last().data('id');
	id += 1;
	var html = '<div class="point" id="point'+id+'" data-id="'+id+'">'
			+'<p>チェックポイント名: <input type="text" name="pointName'+id+'" value="tempPointName"></p>'
			+'<p>概要説明: <input type="text" name="publicDescription'+id+'" value="tempDescription"></p>'
			+'<p>詳細説明: <input type="text" name="privateDescription'+id+'" value="tempDescription"></p>'
			+'<p><input type="button" class="removePoint" value="削除"></p>'
			+'<p>--------------------------------------------------------------------------------</p>'
			+'</div>';
	$('#points').append(html);
});

$(document).on('click', '.removePoint', function() {
	var id = $(this).parent().parent().attr('data-id');
	var last_id = $('.point').last().attr('data-id');
	
	if(last_id != 0) {
		$('#point'+id).remove();
		for(i=parseInt(id)+1; i<=parseInt(last_id); i++) {
			var j=i-1;
			$('#point'+i).attr('data-id', j);
			$('#point'+i+' [name=pointName'+i+']').attr('name', 'pointName'+j);
			$('#point'+i+' [name=publicDescription'+i+']').attr('name', 'publicDescription'+j);
			$('#point'+i+' [name=privateDescription'+i+']').attr('name', 'privateDescription'+j);
			$('#point'+i).attr('id', 'point'+j);
			//alert($('#tickets').html());
		}
	}
});
</script>
</html>

