<?php
	require_once("php/sessionInit.php");
	require_once("php/config.php");
	require_once(dirname(__FILE__) . "/php/functions.php");


	$stamprally = new StampRally($_GET["id"]);
	$stamprallyInfo = array();
	$stamprallyInfo['name'] = $stamprally->getColumnValue("stamprally_name");
	$stamprallyInfo['masterName'] = $stamprally->getColumnValue("master_name");
	$stamprallyInfo['place'] = $stamprally->getColumnValue("place");
	$stamprallyInfo['description'] = $stamprally->getColumnValue("description");
	$stamprallyInfo['startDate'] = $stamprally->getColumnValue("start_date");
	$stamprallyInfo['endDate'] = $stamprally->getColumnValue("end_date");
	//var_dump($stamprallyInfo['startDate']);
	
	$datetime = $stamprallyInfo['startDate'];
	$d = explode(" ", $datetime);
	$sDate = $d[0];
	$t = explode(":", $d[1]);
	$sTime = $t[0] .':'. $t[1];
	
	
	$datetime = $stamprallyInfo['endDate'];
	$d = explode(" ", $datetime);
	$eDate = $d[0];
	$t = explode(":", $d[1]);
	$eTime = $t[0] .':'. $t[1];
	
	$checkpointIDs = $stamprally->getAllCheckpointID();
	$ticketIDs = $stamprally->getAllTicketID();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyStamp!!</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/hacku2.css">
</head>
<body>
<?php include (HEADER2_NAME); ?>
<div id="page">
	<div id="contents">
		<?php include (MENU_NAME); ?>
		<div id="main">
			<form action="editStamprallyProcess.php" method="post">
			<h1>スタンプラリー</h1>
			<p>スタンプラリー名: <input type="text" name="stamprallyName" value=<?php echo h($stamprallyInfo['name']); ?>></p>
			<p>主催者: <input type="text" name="masterName" value=<?php echo h($stamprallyInfo['masterName']); ?>></p>
			<p>住所: <input type="text" name="place" value=<?php echo h($stamprallyInfo['place']); ?>></p>
			<p>説明: <textarea name="description" ><?php echo h($stamprallyInfo['description']); ?></textarea>
			<p>開始日: <input type="date" name="startDate" value=<?php echo h($sDate); ?>></p>
			<p>開始時刻: <input type="time" name="startTime" value=<?php echo h($sTime); ?>></p>
			<p>終了日: <input type="date" name="endDate" value=<?php echo h($eDate); ?>></p>
			<p>終了時刻: <input type="time" name="endTime" value=<?php echo h($eTime); ?>></p>
			<hr>
			
			<h1>チェックポイント</h1>
			<div id="points">
				<?php $n=0; foreach($checkpointIDs as $checkpointID): ?>
				<?php $cp = new Checkpoint($checkpointID); ?>
				<div class="point" id="point<?php echo $n; ?>" data-id="<?php echo $n; ?>">
					<input type="hidden" name="pointID<?php echo $n; ?>" value="<?php echo $checkpointID; ?>">
					<p>チェックポイント名: <input type="text" name="pointName<?php echo $n; ?>" value="<?php echo $cp->getColumnValue('checkpoint_name'); ?>"></p>
					<p>概要説明: <input type="text" name="publicDescription<?php echo $n; ?>" value="<?php echo $cp->getColumnValue('public_description'); ?>"></p>
					<p>詳細説明: <input type="text" name="privateDescription<?php echo $n; ?>" value="<?php echo $cp->getColumnValue('private_description'); ?>"></p>
					<p><input type="button" class="removePoint" value="削除"></p>
					<hr>
				</div>
				<?php $n++; endforeach; ?>
			</div>
			<p><input type="button" id="addPoint" value="チェックポイント追加"></p>
			
			<h1>チケット</h1>
			<div id="tickets">
				<?php $n=0; foreach($ticketIDs as $ticketID): ?>
				<?php $tk = new Ticket($ticketID); ?>
				<div class="ticket" id="ticket<?php echo $n; ?>" data-id="<?php echo $n; ?>">
					<input type="hidden" name="ticketID<?php echo $n; ?>" value="<?php echo $ticketID; ?>">
					
					<p>チケット名: <input type="text" name="ticketName<?php echo $n; ?>" value="<?php echo $tk->getColumnValue('ticket_name'); ?>"></p>
					<p>説明: <input type="text" name="description<?php echo $n; ?>" value="<?php echo $tk->getColumnValue('description'); ?>"></p>
					<p>種類: <select name="type<?php echo $n; ?>">
					<?php if($tk->getColumnValue('type') === 'food'): ?>
						<option value="food" selected>飲食</option>
						<option value="shopping">買い物</option>
						<option value="gift">贈呈</option>
					<?php endif; ?>
					<?php if($tk->getColumnValue('type') === 'shopping'): ?>
						<option value="food">飲食</option>
						<option value="shopping" selected>買い物</option>
						<option value="gift">贈呈</option>
					<?php endif; ?>
					<?php if($tk->getColumnValue('type') === 'gift'): ?>
						<option value="food">飲食</option>
						<option value="shopping">買い物</option>
						<option value="gift" selected>贈呈</option>
					<?php endif; ?>
					</select></p>
					<p>上限枚数: <input type="number" name="limitTicketNum<?php echo $n; ?>" value="<?php echo $tk->getColumnValue('limit_ticket_num'); ?>"></p>
					<?php
						$datetime = $tk->getColumnValue('limit_date');
						$d = explode(" ", $datetime);
						$date = $d[0];
						$t = explode(":", $d[1]);
						$time = $t[0] .':'. $t[1];
					?>
					<p>交換期限: <input type="date" name="limitDate<?php echo $n; ?>" value="<?php echo $date; ?>"></p>
					<p>交換期限（時刻）: <input type="time" name="limitTime<?php echo $n; ?>" value="<?php echo $time; ?>"></p>
					<p>チケット獲得に必要なチェックポイント数: <input type="number" name="requiredCheckpointNum<?php echo $n; ?>" value="<?php echo $tk->getColumnValue('required_checkpoint_num'); ?>"></p>
					<p><input type="button" class="removeTicket" value="削除"></p>
					<hr>
				</div>
				<?php $n++; endforeach; ?>
			</div>
			<p><input type="button" id="addTicket" value="チケット追加"></p>
			
			<input type="hidden" name="stamprallyID" value="<?php echo $_GET['id']; ?>">
			<input type="submit">
			</form>
			
		</div>
	</div>
</div>
<?php include (FOOTER_NAME); ?>
</body>
<script>
$(document).on('click', '#addPoint', function() {
	var id = $('.point').last().data('id');
	id += 1;
	var html = '<div class="point" id="point'+id+'" data-id="'+id+'">'
			+'<p>チェックポイント名: <input type="text" name="pointName'+id+'" value="tempPointName"></p>'
			+'<p>概要説明: <input type="text" name="publicDescription'+id+'" value="tempDescription"></p>'
			+'<p>詳細説明: <input type="text" name="privateDescription'+id+'" value="tempDescription"></p>'
			+'<p><input type="button" class="removePoint" value="削除"></p>'
			+'<hr>'
			+'</div>';
	$('#points').append(html);
});

$(document).on('click', '.removePoint', function() {
	var id = $(this).parent().parent().attr('data-id');
	var last_id = $('.point').last().attr('data-id');
	
	if(last_id != 0) {
		$('#point'+id).remove();
		for(i=parseInt(id)+1; i<=parseInt(last_id); i++) {
			var j=i-1;
			$('#point'+i).attr('data-id', j);
			$('#point'+i+' [name=pointID'+i+']').attr('name', 'pointID'+j);
			$('#point'+i+' [name=pointName'+i+']').attr('name', 'pointName'+j);
			$('#point'+i+' [name=publicDescription'+i+']').attr('name', 'publicDescription'+j);
			$('#point'+i+' [name=privateDescription'+i+']').attr('name', 'privateDescription'+j);
			$('#point'+i).attr('id', 'point'+j);
			//alert($('#tickets').html());
		}
	}
});

$(document).on('click', '#addTicket', function() {
	var id = $('.ticket').last().data('id');
	id += 1;
	var html = '<div class="ticket" id="ticket'+id+'" data-id="'+id+'">'
		+'<p>チケット名: <input type="text" name="ticketName'+id+'" value="tempTicketName"></p>'
		+'<p>説明: <input type="text" name="description'+id+'" value="tempDescription"></p>'
		+'<p>種類: <select name="type'+id+'">'
		+'<option value="food">飲食</option>'
		+'<option value="shopping">買い物</option>'
		+'<option value="gift">贈呈</option>'
		+'</select></p>'
		+'<p>上限枚数: <input type="number" name="limitTicketNum'+id+'" value="1"></p>'
		+'<p>交換期限: <input type="date" name="limitDate'+id+'" value="2011-01-01"></p>'
		+'<p>交換期限（時刻）: <input type="time" name="limitTime'+id+'" value="20:40"></p>'
		+'<p>チケット獲得に必要なチェックポイント数: <input type="number" name="requiredCheckpointNum'+id+'" value="1"></p>'
		+'<p><input type="button" class="removeTicket" value="削除"></p>'
		+'<hr>'
		+'</div>';
	$('#tickets').append(html);
});

$(document).on('click', '.removeTicket', function() {
	var id = $(this).parent().parent().attr('data-id');
	var last_id = $('.ticket').last().attr('data-id');
	//alert(id+' '+last_id);
	//alert($('#tickets').html());
	if(last_id != 0) {
		$('#ticket'+id).remove();
		for(i=parseInt(id)+1; i<=parseInt(last_id); i++) {
			var j=i-1;
			$('#ticket'+i).attr('data-id', j);
			$('#ticket'+i+' [name=ticketID'+i+']').attr('name', 'ticketID'+j);
			$('#ticket'+i+' [name=ticketName'+i+']').attr('name', 'ticketName'+j);
			$('#ticket'+i+' [name=description'+i+']').attr('name', 'description'+j);
			$('#ticket'+i+' [name=type'+i+']').attr('name', 'type'+j);
			$('#ticket'+i+' [name=limitTicketNum'+i+']').attr('name', 'limitTicketNum'+j);
			$('#ticket'+i+' [name=limitDate'+i+']').attr('name', 'limitDate'+j);
			$('#ticket'+i+' [name=limitTime'+i+']').attr('name', 'limitTime'+j);
			$('#ticket'+i+' [name=requiredCheckpointNum'+i+']').attr('name', 'requiredCheckpointNum'+j);
			$('#ticket'+i).attr('id', 'ticket'+j);
			//alert($('#tickets').html());
		}
	}
});
</script>
</html>

