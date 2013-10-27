<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once("php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	// ユーザの情報を格納
	$user = new User($_SESSION['me']->id);

	$ticket = new Ticket($_GET['id']);
	$ticketInfo = array();
	$ticketInfo['name'] = $ticket->getColumnValue("ticket_name");
	$ticketInfo['id'] = $ticket->getColumnValue("ticket_id");
	$ticketInfo['description'] = $ticket->getColumnValue("description");
	$ticketInfo['stamprallyID'] = $ticket->getColumnValue("stamprally_id");
	$ticketInfo['state'] = $user->getTicketState($ticketInfo['id']);
	$ticketInfo['type'] = $ticket->getColumnValue("type");
	switch ($ticketInfo['type']) {
		case 'food':
			$ticketInfo['imgURL'] = SITE_URL. "imgs/food.gif";
			break;
		case 'shopping':
			$ticketInfo['imgURL'] = SITE_URL. "imgs/shopping.gif";
			break;
		case 'gift':
			$ticketInfo['imgURL'] = SITE_URL. "imgs/presents.gif";
			break;		
		default:
			$ticketInfo['imgURL'] = SITE_URL. "imgs/shopping.gif";
			break;
	}


	// スタンプラリーの情報を格納
	$stamprally = new StampRally($ticketInfo['stamprallyID']);
	$stamprallyName = $stamprally->getColumnValue("stamprally_name");
	$stamprallyURL = SITE_URL. STAMPRALLY_URL. "?id=". $ticketInfo['stamprallyID'];
	// チケットを利用済みにする処理
	$user->useTicket($_GET['id']);
	// チケットを減らす
	$tNum = (int)($ticket->getColumnValue('limit_ticket_num'));
	$tNum = $tNum - 1;
	if($tNum < 0)
		$tNum = 0;
	$ticket->update(null, null, null, null, null, $tNum, null, null);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyRally</title>
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<?php include (HEADER_NAME); ?> 
<div id="page">
<div id = "contents">
    <?php include (MENU_NAME); ?>
	<div id="main">
		<?php if($ticketInfo['state'] == 0):?><!-- 所持していないとき -->
			<h1><?php echo h($ticketInfo['name']. "はまだ所持していません！");?></h1>
			<p> <a href="<?php echo h($stamprallyURL); ?>"><?php echo h($stamprallyName); ?></a>で獲得できます！</p>
		<?php endif; ?><!-- 所持していないとき -->
		<?php if($ticketInfo['state'] == 1):?><!-- 未使用のチケットを所持しているとき -->
			<h1><?php echo h($ticketInfo['name']. "を利用しました！");?></h1>
			<img src="<?php echo h($ticketInfo['imgURL']); ?>">
			<p>説明:<?php echo h($ticketInfo['description']); ?></p>
			<p> <a href="<?php echo h($stamprallyURL); ?>"><?php echo h($stamprallyName); ?></a>で獲得しました</p>
		<?php endif; ?><!-- 未使用のチケットを所持しているとき -->
		<?php if($ticketInfo['state'] == 2):?><!-- 使用済みのチケットを所持しているとき -->
			<h1><?php echo h($ticketInfo['name']. "は既に利用済みです！");?></h1>
			<p><a href="<?php echo h($stamprallyURL); ?>"><?php echo h($stamprallyName); ?></a>で他のチケットを獲得しましょう!</p>
		<?php endif; ?><!-- 使用済みのチケットを所持しているとき -->
	</div>
</div>
</div>
<?php include (FOOTER_NAME); ?>

</body>
</html>

