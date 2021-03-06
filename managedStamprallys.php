<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once(dirname(__FILE__) . "/php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	// デバッグ用の定義
	// define("tempTwID", 127982310);

	// スタンプラリー詳細のURL
	define("DETAIL_URL", "managedStamprallyDetail.php");
	define("EDIT_URL", "editStamprally.php");

	$usr = new User($_SESSION['me']->id);
	
	// チェックポイントやチケットが存在しないスタンプラリーのゴミデータを削除する
	// こういうゴミデータはスタンプラリーだけ登録してチェックポイントやチケットを登録しなかったときにできる
	$stamprallyIDs = $usr->getAllManagedStamprallyID();
	//var_dump($stamprallyIDs);
	foreach($stamprallyIDs as $stamprallyID) {
		$s = new Stamprally($stamprallyID);
		$countC = count( $s->getAllCheckpointID() );
		$countT = count( $s->getAllTicketID() );
		//var_dump($countC);
		//var_dump($countT);
		if( $countC == 0 || $countT == 0 ) {
			$s->remove();
		}
	}
	
	$allManagedStamprallyID = $usr->getAllManagedStamprallyID();
	
	
	// ----------HTMLで利用する変数----------
	$allStamprallyInfo = array();// ユーザーが参加しているスタンプラリーの名前が格納された配列
	// 'name' => スタンプラリー名
	// 'url' => スタンプラリー詳細へのURL
	// ----------HTMLで利用する変数----------

	// 各スタンプラリーIDの名前を配列に入れる
	foreach ($allManagedStamprallyID as $managedStamprallyID) {
		$tempStamprallyInfo = array();
		$managedStamprally = new StampRally($managedStamprallyID);
		
		$tempStamprallyInfo['id'] = $managedStamprallyID;
		$tempStamprallyInfo['url'] = DETAIL_URL. "?id=". $managedStamprallyID;
		$tempStamprallyInfo['editUrl'] = EDIT_URL. "?id=". $managedStamprallyID;
		$tempStamprallyInfo['name'] = $managedStamprally->getColumnValue("stamprally_name");

		array_push($allStamprallyInfo, $tempStamprallyInfo);
	}
	
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理中のスタンプラリー - EasyRally</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
   	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/hacku2.css">
    <link rel="stylesheet" href="css/deleteStamprally.css">
</head>
<body>
<?php include (HEADER2_NAME); ?>
<div id="page">
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1><img src="imgs/kaisaityu01.gif"></h1>
			<ul>
				<?php foreach ($allStamprallyInfo as $stamprallyInfo) : ?>
					<li id="stamprallyList<?php echo h($stamprallyInfo['id']); ?>" data-stamprally-id="<?php echo h($stamprallyInfo['id']); ?>">
						<a href=<?php echo h($stamprallyInfo['url']); ?> >
							<?php echo h($stamprallyInfo["name"]); ?>
						</a>
						<a href=<?php echo h($stamprallyInfo['editUrl']); ?>>[編集]</a>
						<span class="deleteStamprally">[削除]</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
<?php include (FOOTER_NAME); ?>

<script>
$(document).on('click', '.deleteStamprally', function() {
	var name = $(this).prev().prev().text();
	var id = $(this).parent().data('stamprally-id');
	if (confirm(name + "を本当に削除しますか?: " + id)) {
		$.post('deleteStamprally.php', {
		    id: id
		}, function(rs) {
			$('#stamprallyList'+id).fadeOut(800);
		});
	}
});
</script>
</body>

</html>

