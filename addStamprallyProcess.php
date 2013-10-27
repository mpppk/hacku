<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once(dirname(__FILE__) . "/php/allRequire.php");


	// デバッグ用の定義
	// define("tempTwID", 127982310);

	// 
	$startDate = $_POST["startDay"]. " ". $_POST["startTime"]. ":00";
	$endDate = $_POST["endDay"]. " ". $_POST["endTime"]. ":00";
	//var_dump($startDate);
	//var_dump($endDate);
	
	// 住所から緯度経度を取得
	$coordinates = getCoordinates($_POST["place"]);
	//var_dump($coordinates);

	$addedStamprallyID = StampRally::add($_POST["stamprallyName"],
		$_SESSION['me']->id,
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
    <title>EasyRally!!</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/hacku2.css">

</head>
<body>
<?php include (HEADER2_NAME); ?>
<div id="page">
		<div id="contents">
		<?php include (MENU_NAME); ?>
        	<div id="main">
			<h1>新しいスタンプラリーを登録しました!</h1>
	 		<p>スタンプラリー名：<?php echo $_POST["stamprallyName"]; ?></p>
			<p>住所：<?php echo $_POST["place"]; ?></p>
			<p>説明:<?php echo $_POST["description"]; ?></p>
			<p>開始日時:<?php echo $startDate; ?></p>
			<p>終了日時:<?php echo $endDate; ?></p>
            <hr>
            
			
			<h1>チェックポイントを登録してください（仮）</h1>
			<form action="addCheckpointProcess.php" method="post">
			<input type="hidden" name="stamprallyID" value="<?php echo $addedStamprallyID; ?>">
			<div id="points">
				<div class="point" id="point0" data-id="0">
                <table class = "table">
					<tr><th>チェックポイント名: </th><th><input type="text" name="pointName0" value="tempPointName"></th></tr>
					<tr><th>概要説明: </th><th><input type="text" name="publicDescription0" value="tempDescription"></th></tr>
					<tr><th>詳細説明: </th><th><input type="text" name="privateDescription0" value="tempDescription"></th></tr>
					<tr><th colspan=2><input type="button" class="removePoint" value="削除"></th></tr>
				</table>
                <hr>
                </div>
                </div>
            	<table class="table">
                	<tr><th colspan=2><input type="button" id="addPoint" value="チェックポイント追加"></th></tr>
            		<tr><th colspan=2><input type="submit" /></th></tr>
                </table>
                </form>
            </div>
		</div>
    </div>
<?php include (FOOTER_NAME); ?>
</body>
<script>
$(document).on('click', '#addPoint', function() {
	var id = $('.point').last().data('id');
	id += 1;
	var html = '<div class="point" id="point'+id+'" data-id="'+id+'">'
			+'<table class = "table">'
			+'<tr><th><p>チェックポイント名: </th><th><input type="text" name="pointName'+id+'" value="tempPointName"></p></th></tr>'
			+'<tr><th><p>概要説明: </th><th><input type="text" name="publicDescription'+id+'" value="tempDescription"></p></th></tr>'
			+'<tr><th><p>詳細説明: </th><th><input type="text" name="privateDescription'+id+'" value="tempDescription"></p></th></tr>'
			+'<tr><th colspan=2><input type="button" class="removePoint" value="削除"></th></tr>'
			+'</table>'
			+'<hr>'
			+'</div>';
	$('#points').append(html);
});

$(document).on('click', '.removePoint', function() {
	var id = $(this).parent().parent().parent().parent().parent().attr('data-id');
	var last_id = $('.point').last().attr('data-id');
	//alert(id+' '+last_id);
	
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

