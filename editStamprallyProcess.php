<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once(dirname(__FILE__) . "/php/allRequire.php");
	
	$apiKey = 'AIzaSyBbMgJKHSnVsnay6RnKlEYGHTd0N4GL0sE';
	$apiURL = 'https://www.googleapis.com/urlshortener/v1/url?key=' . $apiKey;
	
	//var_dump($_POST);
	
	$post['masterName']		= $_POST['masterName'];
	$post['place']			= $_POST['place'];
	$post['description']	= $_POST['description'];
	$post['startDate']		= $_POST['startDate'];
	$post['endDate']		= $_POST['endDate'];
	$stamprallyID			= $_POST['stamprallyID'];
	
	$pPos = array();
	$pIDs = array();
	for($i=0; ; $i++) {
		$id = 'pointID';
		$name = 'pointName';
		$pubDesc = 'publicDescription';
		$priDesc = 'privateDescription';
		$url = 'pointURL';
		
		if($_POST[$name.$i] == NULL)
			break;
		
		$pPos[$i][$name] = $_POST[$name.$i];
		$pPos[$i][$pubDesc] = $_POST[$pubDesc.$i];
		$pPos[$i][$priDesc] = $_POST[$priDesc.$i];
		
		if($_POST[$id.$i] == NULL) {
			// レコード新規追加
			$addedCheckpointID = Checkpoint::add($pPos[$i][$name], $pPos[$i][$pubDesc], $pPos[$i][$priDesc], $stamprallyID, 'none');
			array_push($pIDs, $addedCheckpointID);
			
			$longURL = 'http://www6063ue.sakura.ne.jp/hacku/checkCheckpointProcess.php?id=' . $addedCheckpointID;
			$params = json_encode(array('longUrl' => $longURL));
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $apiURL);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$res = json_decode(curl_exec($curl));
			curl_close($curl);
			//var_dump($res);
			$shortURL = $res->id;
			
			$checkpoint = new Checkpoint($addedCheckpointID);
			$checkpoint->update(null, null, null, null, $shortURL);
			$pPos[$i][$url] = $shortURL;
			
		} else {
			// レコード変更
			array_push($pIDs, (int)$_POST[$id.$i]);
			$checkpoint = new Checkpoint($_POST[$id.$i]);
			$pPos[$i][$url] = $checkpoint->getColumnValue('url');
			
			$checkpoint->update($pPos[$i][$name],
				$pPos[$i][$pubDesc],
				$pPos[$i][$priDesc],
				$stamprallyID,
				null);
		}
	}
	
	$tPos = array();
	$tIDs = array();
	for($i=0; ; $i++) {
		$id = 'ticketID';
		$name = 'ticketName';
		$desc = 'description';
		$type = 'type';
		$lmtTNum = 'limitTicketNum';
		$lmtDate = 'limitDate';
		$lmtTime = 'limitTime';
		$reqCNum = 'requiredCheckpointNum';
		$url = 'ticketURL';
		
		if($_POST[$name.$i] == NULL)
			break;
		
		$tPos[$i][$name] = $_POST[$name.$i];
		$tPos[$i][$desc] = $_POST[$desc.$i];
		$tPos[$i][$type] = $_POST[$type.$i];
		$tPos[$i][$lmtTNum] = (int)$_POST[$lmtTNum.$i];
		$tPos[$i][$lmtDate] = $_POST[$lmtDate.$i] . ' ' .$_POST[$lmtTime.$i]. ':00';
		$tPos[$i][$reqCNum] = (int)$_POST[$reqCNum.$i];
		
		if($_POST[$id.$i] == NULL) {
			// レコード新規追加
			$addedTicketID = Ticket::add($stamprallyID, $tPos[$i][$name], $tPos[$i][$lmtDate], $tPos[$i][$reqCNum], $tPos[$i][$lmtTNum]);
			array_push($tIDs, $addedTicketID);
			
			$longURL = 'http://www6063ue.sakura.ne.jp/hacku/useTicket.php?id=' . $addedTicketID;
			$params = json_encode(array('longUrl' => $longURL));
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $apiURL);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$res = json_decode(curl_exec($curl));
			curl_close($curl);
			//var_dump($res);
			$shortURL = $res->id;
			
			$ticket = new Ticket($addedTicketID);
			$ticket->update(null, 
				null,
				$tPos[$i][$desc],
				null,
				null,
				null,
				$tPos[$i][$type],
				$shortURL);
			$tPos[$i][$url] = $shortURL;
			
		} else {
			// レコード変更
			array_push($tIDs, (int)$_POST[$id.$i]);
			$ticket = new Ticket($_POST[$id.$i]);
			$tPos[$i][$url] = $ticket->getColumnValue('url');
			
			$ticket->update($stamprallyID,
				$tPos[$i][$name],
				$tPos[$i][$desc],
				$tPos[$i][$lmtDate],
				$tPos[$i][$reqCNum],
				$tPos[$i][$lmtTNum],
				$tPos[$i][$type],
				null);
		}
	}
	
	//DBからとってきたほうにあって、p/tIDsにないものを削除
	$stamprally = new Stamprally($stamprallyID);
	$pDBs = $stamprally->getAllCheckpointID();
	$tDBs = $stamprally->getAllTicketID();
	foreach($pDBs as $pID) {
		if(!in_array($pID, $pIDs)) {
			$checkpoint = new Checkpoint($pID);
			$checkpoint->remove();
		}
	}
	foreach($tDBs as $tID) {
		if(!in_array($tID, $tIDs)) {
			$ticket = new Ticket($tID);
			$ticket->remove();
		}
	}
	//var_dump($pDBs);
	//var_dump($tDBs);
	//var_dump($pIDs);
	//var_dump($tIDs);
	
	$typeName = array();
	$typeName['food'] = "飲食";
	$typeName['shopping'] = "買い物";
	$typeName['gift'] = "贈呈";
	//var_dump($pPos);
	//var_dump($tPos);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>EasyStamp!!</title>
	<link rel="stylesheet" href="css/hacku2.css">
</head>
<body>
<?php include (HEADER2_NAME); ?>
<?php include (MENU_NAME); ?>
<div id="main">
	<div id="contents">
		<h1>内容を変更しました</h1>
		
		
		<h1>スタンプラリー</h1>
		<p>主催者名：<?php echo $post['masterName']; ?></p>
		<p>場所：<?php echo $post['place']; ?></p>
		<p>説明:<?php echo $post['description']; ?></p>
		<p>開始日時:<?php echo $post['startDate']; ?></p>
		<p>終了日時:<?php echo $post['endDate']; ?></p>
		
		
		<h1>チェックポイント</h1>
		<?php foreach($pPos as $p): ?>
		<p>チェックポイント名：<?php echo $p['pointName']; ?></p>
		<p>概要説明：<?php echo $p['publicDescription']; ?></p>
		<p>詳細説明：<?php echo $p['privateDescription']; ?></p>
		<p>URL:<input type="text" value="<?php echo $p['pointURL']; ?>"></p>
		<p>--------------------------------------------------------------------------------</p>
		<?php endforeach; ?>
		
		
		<h1>チケット</h1>
		<?php foreach($tPos as $p): ?>
		<p>チケット名：<?php echo $p['ticketName']; ?></p>
		<p>説明：<?php echo $p['description']; ?></p>
		<p>種類：<?php echo $typeName[$p['type']]; ?></p>
		<p>上限枚数：<?php echo $p['limitTicketNum']; ?></p>
		<p>交換期限：<?php echo $p['limitDate']; ?></p>
		<p>チケット獲得に必要なチェックポイント数：<?php echo $p['requiredCheckpointNum']; ?></p>
		<p>交換先URL:<input type="text" value="<?php echo $p['ticketURL']; ?>"></p>
		<p>--------------------------------------------------------------------------------</p>
		<?php endforeach; ?>
		
	</div>
</div>
<?php include (FOOTER_NAME); ?>
</body>
</html>

