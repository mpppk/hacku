<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once("php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");
	// define("tempTwID", 127982310);
	// チケット詳細のURL
	define("DETAIL_URL", "ticketDetail.php");

	$usr = new User($_SESSION['me']->id);
	$allTicketID = $usr->getGotTickets();

	// ----------HTMLで利用する変数----------
	$allTicketInfo = array();// ユーザーが取得済みのチケット名が格納された配列
	// 'name' => スタンプラリー名
	// 'url' => スタンプラリー詳細へのURL
	// ----------HTMLで利用する変数----------

	// 各チケットIDの名前を配列に入れる
	foreach ($allTicketID as $ticketID) {
		$tempTicketInfo = array();
		$gotTicket = new Ticket($ticketID);

		$tempTicketInfo['url'] = DETAIL_URL. "?id=". $ticketID;
		$tempTicketInfo['name'] = $gotTicket->getColumnValue("ticket_name");

		array_push($allTicketInfo,$tempTicketInfo);
	}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>取得したチケット一覧 - EasyRally</title>
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<?php include (HEADER_NAME); ?>
<div id="page">
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1><img src="imgs/ticketichiran01.gif"></h1>
			<!-- 取得済みチケットを表示 -->
			<ul>
				<?php foreach ($allTicketInfo as $ticketInfo) : ?>
					<li>
						<a href=<?php echo h($ticketInfo['url']); ?>>
							<?php echo h($ticketInfo['name']); ?>
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

