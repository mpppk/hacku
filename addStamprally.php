<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once(dirname(__FILE__) . "/php/allRequire.php");

	$usr = new User($_SESSION['me']->id);
	$usrScreenName = $usr->getColumnValue("user_name");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyStamp!!</title>
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<div id="page">
	<?php include (HEADER_NAME); ?>
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1><img src="imgs/sinkikaisai01.gif"></h1>
			<form action="addStamprallyProcess.php" method="post">
			<p>スタンプラリー名: <input type="text" name="stamprallyName" value="tempStamprallyName"></p>
			<p>主催者名: <input type="text" name="masterName" value="tempMasterName"></p>
			<p>場所: <input type="text" name="place" value="tempPlace"></p>
			<p>説明: <input type="text" name="description" value="tempDescription"></p>
			<p>開始日: <input type="date" name="startDay" value="2011-01-01"></p>
			<p>開始時刻: <input type="time" name="startTime" value="20:40"></p>
			<p>終了日: <input type="date" name="endDay" value="2011-01-01"></p>
			<p>終了時刻: <input type="time" name="endTime" value="20:40"></p>
			<input type="submit" />
			</form>
		</div>
		<?php include (FOOTER_NAME); ?>
	</div>
</div>

</body>
</html>

