<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once("php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	$checkpoint = new checkpoint($_GET["id"]);
	$checkpointInfo = array();
	$checkpointInfo['name'] = $checkpoint->getColumnValue("checkpoint_name");
	$checkpointInfo['id'] = $checkpoint->getColumnValue("checkpoint_id");
	$checkpointInfo['stamprallyId'] = $checkpoint->getColumnValue("stamprally_id");
	$checkpointInfo['publicDescription'] = $checkpoint->getColumnValue("public_description");
	$checkpointInfo['privateDescription'] = $checkpoint->getColumnValue("private_description");

	$stamprally = new StampRally($checkpointInfo['stamprallyId']);
	$stamprallyName = $stamprally->getColumnValue("stamprally_name");
	$stamprallyURL = SITE_URL. STAMPRALLY_URL;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>チェックポイント情報 - EasyRally</title>
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<?php include (HEADER_NAME); ?>
<div id="page">
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1><?php echo h($checkpointInfo['name']);?></h1>
			<p><a href=<?php echo h("\"". $stamprallyURL. "\""); ?>> <?php echo h($stamprallyName) ?> </a> のチェックポイント</p>
			<p>説明:<?php echo h($checkpointInfo['publicDescription']);?></p>
			<p>詳細説明:<?php echo h($checkpointInfo['privateDescription']);?></p>
		</div>
	</div>
</div>
<?php include (FOOTER_NAME); ?>
</body>
</html>

