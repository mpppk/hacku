<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once("php/config.php");
	require_once("php/usefulfuncs.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	// デバッグ用の定義
	define("tempTwID", 127982310);
	// チケット詳細へのURL
	define("ticketURL", "http://www6063ue.sakura.ne.jp/hacku/ticketDetail.php");

	// ユーザー情報を取得
	$user = new User(tempTwID);
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
		array_push($allTicketInfo, $tempTicketInfo);
		// var_dump($tempTicketInfo);
	}

	// メッセージ


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyStamp!!</title>
    <link rel="stylesheet" href="css/hacku.css">
    <script charset="UTF-8" src="http://js.api.olp.yahooapis.jp/OpenLocalPlatform/V1/jsapi?appid=dj0zaiZpPVphNFdrbTRqOHMzWSZzPWNvbnN1bWVyc2VjcmV0Jng9NmU-"></script>

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
				<h1><?php echo h($stamprallyInfo['name']. "  ". $userInfo['allCheckedCheckpointsNum']. "/". $stamprallyInfo['allCheckpointsNum']);?></h1>
				<p>主催者: <?php echo h($stamprallyInfo['masterName']); ?></p>
				<p>場所: <?php echo h($stamprallyInfo['place']); ?></p>
				<p>説明: <?php echo h($stamprallyInfo['description']); ?></p>
				<p>開始日時: <?php echo h($stamprallyInfo['startDate']); ?></p>
				<p>終了日時: <?php echo h($stamprallyInfo['endDate']); ?></p>

				<div id="map" style="width:400px; height:250px;"></div>

			</div>
			<h1>取得できるチケット一覧</h1>
			<?php  foreach($allTicketInfo as $ticketInfo) :?>
				<div id=<?php echo h("main". $divNum. "_s"); ?> >
					<h2><a href=<?php echo h(ticketURL. "?id=". $ticketInfo['id']);?>><?php echo h($ticketInfo['name']);?></a><?php echo h(" ". $ticketInfo['gotMsg']) ?></h2>
					<p>説明: <?php echo h($ticketInfo['description']);?></p>
					<p>有効期限: <?php echo h($ticketInfo['limitDate']);?></p>
					<p>必要チェック数: <?php echo h($ticketInfo['requiredCheckpointNum']);?></p>
					<p>配布上限: <?php echo h($ticketInfo['limitTicketNum']);?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<?php include (FOOTER_NAME); ?>
	</div>
</div>
</body>
</html>

