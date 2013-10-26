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
    <title>スタンプラリー追加 - EasyRally</title>
    <link rel="stylesheet" href="css/hacku2.css">
</head>
<body>
<?php include (HEADER2_NAME); ?>
<div id="page">
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1><img src="imgs/sinkikaisai01.gif"></h1>
			<table class="table" >
            <tr>
			<form action="addStamprallyProcess.php" method="post">
			<th><p>スタンプラリー名: </th><th><input type="text" name="stamprallyName" value="tempStamprallyName"></p></th>
			<tr><th><p>主催者名: </th><th><input type="text" name="masterName" value="tempMasterName"></p></th></tr>
			<tr><th><p>場所: </th><th><input type="text" name="place" value="tempPlace"></p></th></tr>
			<tr><th><p>説明: </th><th><input type="text" name="description" value="tempDescription"></p></th></tr>
			<tr><th><p>開始日: </th><th><input type="date" name="startDay" value="2011-01-01"></p></th></tr>
			<tr><th><p>開始時刻: </th><th><input type="time" name="startTime" value="20:40"></p></th></tr>
			<tr><th><p>終了日: </th><th><input type="date" name="endDay" value="2011-01-01"></p></th></tr>
			<tr><th><p>終了時刻: </th><th><input type="time" name="endTime" value="20:40"></p></th></tr>
			<tr><th></th><th><input type="submit" value="登録する！！"/>
			</form></th>
            </tr>
            </table>
		</div>
    </div>
</div>
<?php include (FOOTER_NAME); ?>



</body>
</html>

