<?php
	require_once("php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");

	$ticket = new Ticket($_GET["id"]);
	$ticketInfo = array();
	$ticketInfo['name'] = $ticket->getColumnValue("ticket_name");
	$ticketInfo['description'] = $ticket->getColumnValue("description");
	$ticketInfo['limitDate'] = $ticket->getColumnValue("limit_date");
	$ticketInfo['requiredCheckpointNum'] = 
		$ticket->getColumnValue("required_checkpoint_num");
	$ticketInfo['limitTicketNum'] = $ticket->getColumnValue("limit_ticket_num");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyStamp!!</title>
    <link rel="stylesheet" href="css/hacku.css">
</head>
<body>
<div id="page">
	<?php include (HEADER_NAME); ?>
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1><?php echo h($ticketInfo['name']);?></h1>
			<p>説明:<?php echo h($ticketInfo['description']);?></p>
			<p>有効期限:<?php echo h($ticketInfo['limitDate']);?></p>
			<p>必要チェックポイント数:<?php echo h($ticketInfo['requiredCheckpointNum']);?></p>
			<p>残り配布枚数:<?php echo h($ticketInfo['limitTicketNum']);?></p>
		</div>
		<?php include (FOOTER_NAME); ?>
	</div>
</div>
</body>
</html>

