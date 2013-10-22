<?php
	require_once("php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	$stamprally = new StampRally($_GET["id"]);
	$stamprallyInfo = array();
	$stamprallyInfo['name'] = $stamprally->getColumnValue("stamprally_name");
	$stamprallyInfo['masterName'] = $stamprally->getColumnValue("master_name");
	$stamprallyInfo['place'] = $stamprally->getColumnValue("place");
	$stamprallyInfo['description'] = $stamprally->getColumnValue("description");
	$stamprallyInfo['startDate'] = $stamprally->getColumnValue("start_date");
	$stamprallyInfo['endDate'] = $stamprally->getColumnValue("end_date");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyStamp!!</title>
    ]
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<div id="page">
	<?php include (HEADER_NAME); ?>
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<form action="editStamprallyProcess.php" method="post">
				<p>主催者: <input type="text" name="masterName" value=<?php echo h($stamprallyInfo['masterName']); ?>></p>
				<p>場所: <input type="text" name="place" value=<?php echo h($stamprallyInfo['place']); ?>></p>
				<p>説明: <textarea name="description" ><?php echo h($stamprallyInfo['description']); ?></textarea>
				<p>開始日時: <input type="text" name="startDate" value=<?php echo h($stamprallyInfo['startDate']); ?>></p>
				<p>終了日時: <input type="text" name="endDate" value=<?php echo h($stamprallyInfo['endDate']); ?>></p>
				<input type="submit">
			</form>
		</div>
		<?php include (FOOTER_NAME); ?>
	</div>
</div>
</body>
</html>

