<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once(dirname(__FILE__) . "/php/allRequire.php");

	// デバッグ用の定義
	// define("tempTwID", 127982310);

	//var_dump($_POST);
	
	$stamprallyID = (int)$_POST['stamprallyID'];
	//var_dump($stamprallyID);
	
	$pos = array();
	for($i=0; ; $i++) {
		$name = 'ticketName';
		$desc = 'description';
		$type = 'type';
		$lmtTNum = 'limitTicketNum';
		$lmtDate = 'limitDate';
		$lmtTime = 'limitTime';
		$reqCNum = 'requiredCheckpointNum';
		
		if($_POST[$name.$i] == NULL)
			break;
		
		$pos[$i][$name] = $_POST[$name.$i];
		$pos[$i][$desc] = $_POST[$desc.$i];
		$pos[$i][$type] = $_POST[$type.$i];
		$pos[$i][$lmtTNum] = (int)$_POST[$lmtTNum.$i];
		$pos[$i][$lmtDate] = $_POST[$lmtDate.$i] . ' ' .$_POST[$lmtTime.$i]. ':00';
		$pos[$i][$reqCNum] = (int)$_POST[$reqCNum.$i];
		
		$addedTicketID = Ticket::add($stamprallyID, $pos[$i][$name], $pos[$i][$lmtDate], $pos[$i][$reqCNum], $pos[$i][$lmtTNum]);
		
		$ticket = new Ticket($addedTicketID);
		$ticket->update(null, 
			null,
			$pos[$i][$desc],
			null,
			null,
			null,
			$pos[$i][$type]);
		
	}

	$typeName = array();
	$typeName['food'] = "飲食";
	$typeName['shopping'] = "買い物";
	$typeName['gift'] = "贈呈";

	//var_dump($pos);
	
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyStamp!!</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/hacku.css">

</head>
<body>
<div id="page">
	<?php include (HEADER_NAME); ?>
	<div id = "contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<h1>新しいチケットを登録しました!</h1>
			<?php foreach($pos as $p): ?>
			<p>チケット名：<?php echo $p[$name]; ?></p>
			<p>説明：<?php echo $p[$desc]; ?></p>
			<p>種類：<?php echo $typeName[$p[$type]]; ?></p>
			<p>上限枚数：<?php echo $p[$lmtTNum]; ?></p>
			<p>交換期限：<?php echo $p[$lmtDate]; ?></p>
			<p>チケット獲得に必要なチェックポイント数：<?php echo $p[$reqCNum]; ?></p>
			<p>--------------------------------------------------------------------------------</p>
			<?php endforeach; ?>
		</div>
		<?php include (FOOTER_NAME); ?>
	</div>
</div>

</body>
<script>

</script>
</html>

