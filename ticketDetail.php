<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once("php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	$ticket = new Ticket($_GET["id"]);
	$ticketInfo = array();
	$ticketInfo['name'] = $ticket->getColumnValue("ticket_name");
	$ticketInfo['id'] = $ticket->getColumnValue("ticket_id");
	$ticketInfo['description'] = $ticket->getColumnValue("description");
	$ticketInfo['limitDate'] = $ticket->getColumnValue("limit_date");
	$ticketInfo['requiredCheckpointNum'] = 
		$ticket->getColumnValue("required_checkpoint_num");
	$ticketInfo['limitTicketNum'] = $ticket->getColumnValue("limit_ticket_num");
	$ticketInfo['stamprallyID'] = $ticket->getColumnValue("stamprally_id");

	$stamprally = new StampRally($ticketInfo['stamprallyID']);
	$stamprallyName = $stamprally->getColumnValue("stamprally_name");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>チケット詳細 - EasyRally</title>
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<?php include (HEADER_NAME); ?>
<div id="page">
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1><?php echo h($ticketInfo['name']);?></h1>
            <table class="table">
			<tr><th>取得可能なスタンプラリー:</th><th><?php echo h($stamprallyName);?></th></tr>
			<tr><th>説明:</th><th><?php echo h($ticketInfo['description']);?></th></tr>
			<tr><th>有効期限:</th><th><?php echo h($ticketInfo['limitDate']);?></th></tr>
			<tr><th>必要チェックポイント数:</th><th><?php echo h($ticketInfo['requiredCheckpointNum']);?></th></tr>
			<tr><th>残り配布枚数:</th><th><?php echo h($ticketInfo['limitTicketNum']);?></th></tr>
            </table>
		</div>
	</div>
</div>
<?php include (FOOTER_NAME); ?>
</body>
</html>

