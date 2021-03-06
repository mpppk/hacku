<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once(dirname(__FILE__) . "/php/functions.php");
	require_once(dirname(__FILE__) . "/php/allRequire.php");

	if(!isset($_SESSION['me'])){
		echo "session me timeout<br>";
	}else{
		// var_dump($_SESSION['me']);
	}

	// デバッグ用の定義
	// define("tempTwID", 127982310);

	// ページURL
	define("pageURL", "http://www6063ue.sakura.ne.jp/hacku/stamprallyDetail.php");

	// session情報が取れているかどうか
	if(empty($_SESSION['currentCheckpoint'])){
		echo "currentCheckpoint session timeout<br>";
		$checkpointID = -999;
	}else{
		$checkpointID = $_SESSION['currentCheckpoint'];
	}

	$user = new User($_SESSION['me']->id);
	$userInfo = array();
	$userInfo['id'] = $_SESSION['me']->id;
	$currentCheckpoint = new Checkpoint($checkpointID);
	$currentCheckpointInfo = array();
	$currentCheckpointInfo['id'] = $checkpointID;

	// stamprallyインスタンスを生成
	$currentCheckpointInfo['stamprallyID'] = $currentCheckpoint->getColumnValue('stamprally_id');
	$stamprally = new StampRally($currentCheckpointInfo['stamprallyID']);
	$stamprallyInfo = array();

	// ---------- 事前に格納できる情報は格納しておく ----------
	// チェックポイントの情報を格納
	$currentCheckpointInfo['name'] = $currentCheckpoint->getColumnValue('checkpoint_name');
	$currentCheckpointInfo['privateDescription'] = $currentCheckpoint->getColumnValue("private_description");

	// このチェックポイントが所属するスタンプラリーの情報を格納
	$stamprallyInfo['id'] = $currentCheckpointInfo['stamprallyID'];
	$stamprallyInfo['name'] = $stamprally->getColumnValue("stamprally_name");
	$stamprallyInfo['description'] = $stamprally->getColumnValue("description");
	$stamprallyInfo['allCheckpointsID'] = $stamprally->getAllCheckpointID();
	$stamprallyInfo['allCheckpointsNum'] = count($stamprallyInfo['allCheckpointsID']);
	$stamprallyInfo['allTicketID'] = $stamprally->getAllTicketID();
	// このユーザーがこのスタンプラリーで既にチェック済みのチェックポイントID一覧を取得
	// この時点では今回のチェックは入っていないので注意!!!!!!!!!!!!!!!!!!!!!!!!
	$userInfo['allCheckedCheckpointID'] = $user->getAllCheckedCheckpointID($stamprallyInfo['id']);

	// このチェックポイントがチェック済みかどうか調べる
	$isCheckedCheckpoint = false;
	foreach ($userInfo['allCheckedCheckpointID'] as $checkedCheckpointID) {
		// 現在のチェックポイントと同じIDがあれば
		if($checkedCheckpointID == $checkpointID){
			$isCheckedCheckpoint = true;
			break;
		}
	}

	// ユーザーの情報を格納
	// $userInfo['allCheckedCheckpointID']の後に記述する
	$userInfo['allCheckedCheckpointsNum'] = count($userInfo['allCheckedCheckpointID']);

	// チェック済みでなければチェックする
	if($isCheckedCheckpoint == false){
		// このチェックポイントが所属するスタンプラリーに参加する
		$user->joinStamprally($currentCheckpointInfo['stamprallyID']);

		$user->checkCheckpoint($checkpointID);
		$userInfo['allCheckedCheckpointsNum']++;
		$chkMsg = $currentCheckpointInfo['name']. 'にチェックしました!';
	}else{
		$chkMsg = $currentCheckpointInfo['name']. 'は既にチェック済みです!';
	}


	// 今回のチェックで取得した全てのチケットIDを取得
	$stamprallyInfo['allCurrentGotTicketID'] = array();
	foreach ($stamprallyInfo['allTicketID'] as $ticketID) {
		$tempTicket = new Ticket($ticketID);
		if($userInfo['allCheckedCheckpointsNum'] == $tempTicket->getColumnValue('required_checkpoint_num')){
			array_push($stamprallyInfo['allCurrentGotTicketID'], $ticketID);
			// 取得済みチケットをユーザーに追加
			$user->getTicket($ticketID);

		}
	}
	$currentGotTicketsInfo = array();
	if(count($stamprallyInfo['allCurrentGotTicketID']) == 0){
		$currentGotTicketsInfo[] = NULL;
	}else{
		foreach ($stamprallyInfo['allCurrentGotTicketID'] as $currentGotTicketID) {
			$tempCurrentGotTicketInfo = array();
			$currentGotTicket = new Ticket($currentGotTicketID);
			$tempCurrentGotTicketInfo['id'] = $currentGotTicket->getColumnValue('ticket_id');
			$tempCurrentGotTicketInfo['name'] = $currentGotTicket->getColumnValue('ticket_name');
			$tempCurrentGotTicketInfo['description'] = $currentGotTicket->getColumnValue('description');
			$tempCurrentGotTicketInfo['limitDate'] = $currentGotTicket->getColumnValue('limit_date');
			$tempCurrentGotTicketInfo['requiredCheckpointNum'] = $currentGotTicket->getColumnValue('required_checkpoint_num');
			$tempCurrentGotTicketInfo['limitTicketNum'] = $currentGotTicket->getColumnValue('limit_ticket_num');
			$tempCurrentGotTicketInfo['type'] = $currentGotTicket->getColumnValue('type');
			$currentGotTicketsInfo[] = $tempCurrentGotTicketInfo;
		}
	}

	// twitterボタンのツイート内容
	$twMsg = "「". $stamprallyInfo['name']. "」の「". $currentCheckpointInfo['name']. "」にチェックしました!";
	$twURL = SITE_URL. CHECKPOINT_URL. "?id=". $currentCheckpointInfo['id'];

	// ---------- (デバッグ用) 保持しているデータ ----------
	// echo "ユーザーのデータ一覧<br>";
	// foreach ($userInfo as $key => $value) {
	// 	echo $key. ": ". $value;
	// 	echo "<br>";
	// }
	// echo "<br>";

	// echo "スタンプラリーのデータ一覧<br>";
	// foreach ($stamprallyInfo as $value) {
	// 	var_dump($value);
	// 	echo "<br>";
	// }
	// echo "<br>";

	// echo "チェックポイントのデータ一覧<br>";
	// foreach ($currentCheckpointInfo as $value) {
	// 	var_dump($value);
	// 	echo "<br>";
	// }
	// echo "<br>";

	// echo "チェックポイントのデータ一覧<br>";
	// if($stamprallyInfo['allCurrentGotTicketID'][0] == NULL){
	// 	echo "allCurrentGotTicketID is null";
	// }
	// foreach ($stamprallyInfo['allCurrentGotTicketID'] as $value) {
	// 	var_dump($value);
	// 	echo "<br>";
	// }
	// echo "<br>";

	// if($currentGotTicketsInfo == NULL){
	// 	echo "currentGotTicketInfo is null<br>";
	// }else{
	// 	echo "currentGotTicketsInfo<br>";
	// 	var_dump($currentGotTicketsInfo);
	// 	echo "<br>";
	// }

	// if($isCheckedCheckpoint){
	// 	echo "isCheckedCheckpoint is TRUE";
	// }else{
	// 	echo "isCheckedCheckpoint is FALSE";
	// }

	// echo "currentGotTicketsInfo<br>";
	// var_dump($currentGotTicketsInfo);

	// ---------- (デバッグ用) 保持しているデータ ----------
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>チェックポイント!</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
   	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/hacku.css">
    <link rel="stylesheet" href="css/deleteStamprally.css">
</head>
<body>
<?php include (HEADER_NAME); ?>
<div id="page">
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1><?php echo h($chkMsg); ?></h1>
			<p><?php echo h('チェック数: '. $userInfo['allCheckedCheckpointsNum']. '/'. $stamprallyInfo['allCheckpointsNum']); ?></p>
			<p><?php echo h('チェックポイント説明: '. $currentCheckpointInfo['privateDescription']); ?></p>
			<p>スタンプラリー名: <a href=<?php echo h(pageURL. "?id=". $stamprallyInfo['id']); ?>><?php echo h($stamprallyInfo['name']); ?></a></p>
			<p><?php echo h('スタンプラリー説明: '. $stamprallyInfo['description']); ?></p>
			<h1><?php ?>今回取得したチケット一覧!</h1>
			<?php if($currentGotTicketsInfo[0] == NULL || $isCheckedCheckpoint == true ): ?>
				<p>今回取得したチケットはありません.</p>
			<?php else: ?>
				<?php foreach($currentGotTicketsInfo as $currentGotTicketInfo) : ?>
					<h2><?php echo 'チケット名'. $currentGotTicketInfo['name']; ?></h2>
					<p><?php echo '説明'. $currentGotTicketInfo['description']; ?></p>
					<p><?php echo '有効日'. $currentGotTicketInfo['limitDate']; ?></p>
					<p><?php echo '有効期限'. $currentGotTicketInfo['limitTicketNum']; ?></p>
					<p><?php echo '種類'. $currentGotTicketInfo['type']; ?></p>
				<?php endforeach; ?>
			<?php endif; ?>
			
			<!-- ツイートボタン	 -->
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo h($twURL); ?>" data-text="<?php echo h($twMsg); ?>" data-lang="ja" data-size="large" data-count="none" data-hashtags="EasyRally">ツイート</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div><!-- main -->
	</div><!-- contents -->
</div><!-- page -->
<?php include (FOOTER_NAME); ?>
</body>
</html>


