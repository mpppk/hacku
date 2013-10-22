<?php
	require_once(dirname(__FILE__) . "/php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	// デバッグ用の定義
	define("tempTwID", 127982310);

	// スタンプラリー詳細のURL
	define("DETAIL_URL", "joinedStamprallyDetail.php");

	$usr = new User(tempTwID);
	$allJoinedStamprallyID = $usr->getAllJoinedStamprallyID();
	
	// ----------HTMLで利用する変数----------
	$allStamprallyInfo = array();// ユーザーが参加しているスタンプラリーの名前が格納された配列
	// 'name' => スタンプラリー名
	// 'url' => スタンプラリー詳細へのURL
	// ----------HTMLで利用する変数----------

	// 各スタンプラリーIDの名前を配列に入れる
	foreach ($allJoinedStamprallyID as $joinedStamprallyID) {
		$tempStamprallyInfo = array();
		$joinedStamprally = new StampRally($joinedStamprallyID);
		
		$tempStamprallyInfo['url'] = DETAIL_URL. "?id=". $joinedStamprallyID;
		$tempStamprallyInfo['name'] = $joinedStamprally->getColumnValue("stamprally_name");

		array_push($allStamprallyInfo, $tempStamprallyInfo);
	}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyStamp!!</title>
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<?php include (HEADER_NAME); ?>
<?php include (MENU_NAME); ?>
<div id="main">
	<div id="page">
	<div id="contents">
		<h1><img src="imgs/sankatyu01.gif"></h1>
		<ul>
			<?php foreach ($allStamprallyInfo as $stamprallyInfo) : ?>
				<li>
					<a href=<?php echo h($stamprallyInfo['url']); ?>>
						<?php echo h($stamprallyInfo["name"]); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	</div>
</div>
<?php include (FOOTER_NAME); ?>
</body>
</html>

