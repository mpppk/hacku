<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once("php/config.php");
	require_once("php/usefulfuncs.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	// デバッグ用の定義
	// define("tempTwID", 127982310);
	// チケット詳細へのURL
	define("ticketURL", "http://www6063ue.sakura.ne.jp/hacku/ticketDetail.php");
	define("checkpointURL", "http://www6063ue.sakura.ne.jp/hacku/checkpointDetail.php");

	// ユーザー情報を取得
	$user = new User($_SESSION['me']->id);
	$userInfo = array();

	// スタンプラリー情報を取得
	$stamprally = new StampRally($_GET["id"]);
	$stamprallyInfo = array();
	$stamprallyInfo['name'] = $stamprally->getColumnValue("stamprally_name");
	$stamprallyInfo['id'] = $_GET["id"];
	$stamprallyInfo['masterName'] = $stamprally->getColumnValue("master_name");
	$stamprallyInfo['place'] = $stamprally->getColumnValue("place");
	$stamprallyInfo['lat'] = $stamprally->getColumnValue("lat");
	$stamprallyInfo['lon'] = $stamprally->getColumnValue("lon");
	$stamprallyInfo['description'] = $stamprally->getColumnValue("description");
	$stamprallyInfo['startDate'] = $stamprally->getColumnValue("start_date");
	$stamprallyInfo['endDate'] = $stamprally->getColumnValue("end_date");
	$stamprallyInfo['allCheckpointsID'] = $stamprally->getAllCheckpointID();
	$stamprallyInfo['allCheckpointsNum'] = count($stamprally->getAllCheckpointID());

	// 現在のユーザーがこのスタンプラリーでチェックした数を取得
	$userInfo['allCheckedCheckpointsNum'] = count($user->getAllCheckedCheckpointID($stamprallyInfo['id']));

	// チケット情報を取得
	$allTicketID = $stamprally->getAllTicketID();
	$allTicketInfo = array();
	foreach ($allTicketID as $ticketID) {
		$tempTicketInfo = array();
		$ticket = new Ticket($ticketID);
		$tempTicketInfo['name'] = $ticket->getColumnValue("ticket_name");
		$tempTicketInfo['id'] = $ticket->getColumnValue("ticket_id");
		$tempTicketInfo['type'] = $ticket->getColumnValue("type");

		$tempTicketInfo['description'] = $ticket->getColumnValue("description");
		$tempTicketInfo['limitDate'] = $ticket->getColumnValue("limit_date");
		$tempTicketInfo['requiredCheckpointNum'] = $ticket->getColumnValue("required_checkpoint_num");
		$tempTicketInfo['limitTicketNum'] = $ticket->getColumnValue("limit_ticket_num");
		if($userInfo['allCheckedCheckpointsNum'] >= $tempTicketInfo['requiredCheckpointNum']){
			$tempTicketInfo['gotMsg'] = "取得済み!";
		}else{
			$reminingRequiredTicketsNum = $tempTicketInfo['requiredCheckpointNum'] - $userInfo['allCheckedCheckpointsNum'];
			$tempTicketInfo['gotMsg'] = "あと". $reminingRequiredTicketsNum. "チェックで獲得!";
		}

		// チケットの画像をtypeに応じて決める
		switch ($tempTicketInfo['type']) {
			case 'food':
				$tempTicketInfo['imgURL'] = SITE_URL. "imgs/food.gif";
				break;
			case 'shopping':
				$tempTicketInfo['imgURL'] = SITE_URL. "imgs/shopping.gif";
				break;
			case 'gift':
				$tempTicketInfo['imgURL'] = SITE_URL. "imgs/presents.gif";
				break;
			default:
				$tempTicketInfo['imgURL'] = SITE_URL. "imgs/presents.gif";
				break;
		}

		array_push($allTicketInfo, $tempTicketInfo);
		// var_dump($tempTicketInfo);
	}

	// チェックポイント情報を取得
	$allCheckpointInfo = array();
	$stamprallyInfo['allCheckpointsID'];
	foreach ($stamprallyInfo['allCheckpointsID'] as $tempCheckpointID) {
		$tempCheckpointInfo = array();
		$tempCheckpoint = new Checkpoint($tempCheckpointID);
		$tempCheckpointInfo['id'] = $tempCheckpoint->getColumnValue("checkpoint_id");
		$tempCheckpointInfo['name'] = $tempCheckpoint->getColumnValue("checkpoint_name");
		$tempCheckpointInfo['publicDescription'] = $tempCheckpoint->getColumnValue("public_description");
		$tempCheckpointInfo['privateDescription'] = $tempCheckpoint->getColumnValue("private_description");
		$tempCheckpointInfo['stamprallyId'] = $tempCheckpoint->getColumnValue("stamprally_id");
		$tempCheckpointInfo['url'] = $tempCheckpoint->getColumnValue("url");
		array_push($allCheckpointInfo, $tempCheckpointInfo);

	}

	// rog
	// echo "allCheckpointInfo:<br>";
	// var_dump($allCheckpointInfo);
	// echo "<br";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>スタンプラリー詳細 - EasyRally</title>
    <link rel="stylesheet" href="css/hacku.css">
    <script charset="UTF-8" src="http://js.api.olp.yahooapis.jp/OpenLocalPlatform/V1/jsapi?appid=dj0zaiZpPVphNFdrbTRqOHMzWSZzPWNvbnN1bWVyc2VjcmV0Jng9NmU-"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>

    <script>
    window.onload=function(){
    	var latlng = new Y.LatLng(<?php echo $stamprallyInfo["lat"]; ?>,
    	<?php echo $stamprallyInfo["lon"]; ?>);
    	
    	var map = new Y.Map("map");
    	// 地図を表示
    	map.drawMap(latlng, 15);
    	map.setConfigure('scrollWheelZooom', true);
    	map.addControl( new Y.LayerSetControl() );
    	map.addControl(new Y.SliderZoomControlVertical());
    	map.addControl(new Y.ScaleControl());
    	map.addControl(new Y.CenterMarkControl());
    };
    </script>
</head>
<body>
<div id="page">
	<?php include (HEADER_NAME); ?>
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<div id="main1_s">
				<dl id="acMenu_title"><!-- アコーディオン使う場所 -->
					<dt><h1><?php echo h($stamprallyInfo['name']. "  ". $userInfo['allCheckedCheckpointsNum']. "/". $stamprallyInfo['allCheckpointsNum']);?> ▼</h1></dt><!-- アコーディオンタイトル -->
					<dd><!-- アコーディオン内容 -->
						<p>主催者: <?php echo h($stamprallyInfo['masterName']); ?></p>
						<p>場所: <?php echo h($stamprallyInfo['place']); ?></p>
						<p>説明: <?php echo h($stamprallyInfo['description']); ?></p>
						<p>開始日時: <?php echo h($stamprallyInfo['startDate']); ?></p>
						<p>終了日時: <?php echo h($stamprallyInfo['endDate']); ?></p>
						<div id="map" style="width:400px; height:250px;"></div>
					</dd>
				</dl>
			</div>

			<div id="main2_s">
				<dl id="acMenu_ticket"><!-- アコーディオン使う場所 -->
					<dt><h1>取得できるチケット一覧 ▼</h1></dt><!-- アコーディオンタイトル -->
					<dd><!-- アコーディオン内容 -->
						<?php  foreach($allTicketInfo as $ticketInfo) :?>
							<div id=<?php echo h("main". $divNum. "_s"); ?> >
								<div id="main4_s">
									<h2><a href=<?php echo h(ticketURL. "?id=". $ticketInfo['id']);?>><?php echo h($ticketInfo['name']);?></a><?php echo h(" ". $ticketInfo['gotMsg']) ?></h2>
									<img src="<?php echo h($ticketInfo['imgURL']); ?>">
									<p>説明: <?php echo h($ticketInfo['description']);?></p>
									<p>有効期限: <?php echo h($ticketInfo['limitDate']);?></p>
									<p>必要チェック数: <?php echo h($ticketInfo['requiredCheckpointNum']);?></p>
									<p>配布上限: <?php echo h($ticketInfo['limitTicketNum']);?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</dd><!-- アコーディオン内容 -->
				</dl><!-- アコーディオン使う場所 -->
			</div>
			<div id="main3_s">
				<dl id="acMenu_checkpoint"><!-- アコーディオン使う場所 -->
					<dt><h1>チェックポイント一覧 ▼</h1></dt><!-- アコーディオンタイトル -->
					<dd><!-- アコーディオン内容 -->
						<?php  foreach($allCheckpointInfo as $checkpointInfo) :?>
							<div id="main4_s">
								<div id=<?php echo h("main". $divNum. "_s"); ?> >
									<h2><a href=<?php echo h(checkpointURL. "?id=". $checkpointInfo['id']);?>><?php echo h($checkpointInfo['name']);?></a></h2>
									<p>説明: <?php echo h($checkpointInfo['publicDescription']);?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</dd><!-- アコーディオン内容 -->
				</dl><!-- アコーディオン使う場所 -->

			</div>
		</div>
		<?php include (FOOTER_NAME); ?>
	</div>
</div>
</body>
<script>
	$(function(){
		$("#acMenu_title dt").on("click", function() {
			$(this).next().slideToggle("slow");
		});
	});
	$(function(){
		$("#acMenu_ticket dt").on("click", function() {
			$(this).next().slideToggle("slow");
		});
	});
	$(function(){
		$("#acMenu_checkpoint dt").on("click", function() {
			$(this).next().slideToggle("slow");
		});
	});

</script>
</html>



