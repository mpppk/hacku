<?php
	require_once(dirname(__FILE__) . "/php/sessionInit.php");
	require_once(dirname(__FILE__) . "/php/allRequire.php");

	//var_dump($_POST);
	$stamprallyID = (int)$_POST['stamprallyID'];
	
	$pos = array();
	for($i=0; ; $i++) {
		$name = 'pointName';
		$pubDesc = 'publicDescription';
		$priDesc = 'privateDescription';
		
		if($_POST[$name.$i] == NULL)
			break;
		
		$pos[$i][$name] = $_POST[$name.$i];
		$pos[$i][$pubDesc] = $_POST[$pubDesc.$i];
		$pos[$i][$priDesc] = $_POST[$priDesc.$i];
		
		Checkpoint::add($pos[$i][$name], $pos[$i][$pubDesc], $pos[$i][$priDesc], $stamprallyID, 'none');
	}

	//var_dump($pos);
	
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>EasyRally!!</title>
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
		<h1>新しいチェックポイントを登録しました!</h1>
		<?php foreach($pos as $p): ?>
		<p>チェックポイント名：<?php echo $p[$name]; ?></p>
		<p>概要説明：<?php echo $p[$pubDesc]; ?></p>
		<p>詳細説明：<?php echo $p[$priDesc]; ?></p>
		<hr>
		<?php endforeach; ?>
		
		
		<h1>獲得できるチケットを登録してください</h1>
		<form action="addTicketProcess.php" method="post">
		<input type="hidden" name="stamprallyID" value="<?php echo $stamprallyID; ?>">
		<div id="tickets">
			<div class="ticket" id="ticket0" data-id="0">
                <table class="table">
				<tr><th>チケット名: </th><th><input type="text" name="ticketName0" value="tempTicketName"></th></tr>
				<tr><th>説明: </th><th><input type="text" name="description0" value="tempDescription"></th></tr>
				<tr><th>種類: </th><th><select name="type0">
				<option value="food">飲食</option>
				<option value="shopping">買い物</option>
				<option value="gift">贈呈</option>
				</select></th></tr>
				<tr><th>上限枚数: </th><th><input type="number" name="limitTicketNum0" value="1"></th></tr>
				<tr><th>交換期限: </th><th><input type="date" name="limitDate0" value="2011-01-01"></th></tr>
				<tr><th>交換期限（時刻）: </th><th><input type="time" name="limitTime0" value="20:40"></th></tr>
				<tr><th>チケット獲得に必要なチェックポイント数: </th><th><input type="number" name="requiredCheckpointNum0" value="1"></th></tr>
				<tr><th colspan=2><input type="button" class="removeTicket" value="削除"></th></tr>
                </table>
                <hr>
			</div>
		</div>
        <table class="table">
		<tr><th><input type="button" id="addTicket" value="チケット追加"></th></tr>
		<tr><th><input type="submit" /></th></tr>
        </table>
		</form>
	</div>
</div>
<?php include (FOOTER_NAME); ?>
</body>
<script>
$(document).on('click', '#addTicket', function() {
	var id = $('.ticket').last().data('id');
	id += 1;
	var html = '<div class="ticket" id="ticket'+id+'" data-id="'+id+'">'
		+'<table class="table">'
		+'<tr><th>チケット名: </th><th><input type="text" name="ticketName'+id+'" value="tempTicketName"></th></tr>'
		+'<tr><th>説明: </th><th><input type="text" name="description'+id+'" value="tempDescription"></th></tr>'
		+'<tr><th>種類: </th><th><select name="type'+id+'">'
		+'<option value="food">飲食</option>'
		+'<option value="shopping">買い物</option>'
		+'<option value="gift">贈呈</option>'
		+'</select></th></tr>'
		+'<tr><th>上限枚数: </th><th><input type="number" name="limitTicketNum'+id+'" value="1"></th></tr>'
		+'<tr><th>交換期限: </th><th><input type="date" name="limitDate'+id+'" value="2011-01-01"></th></tr>'
		+'<tr><th>交換期限（時刻）: </th><th><input type="time" name="limitTime'+id+'" value="20:40"></th></tr>'
		+'<tr><th>チケット獲得に必要なチェックポイント数:  </th><th><input type="number" name="requiredCheckpointNum'+id+'" value="1"></th></tr>'
		+'<tr><th colspan=2><input type="button" class="removeTicket" value="削除"></th></tr>'
		+'</table>'
		+'<hr>'
		+'</div>';
	$('#tickets').append(html);
});

$(document).on('click', '.removeTicket', function() {
	var id =  $(this).parent().parent().parent().parent().parent().attr('data-id');
	var last_id = $('.ticket').last().attr('data-id');
	//alert(id+' '+last_id);
	//alert($('#tickets').html());
	if(last_id != 0) {
		$('#ticket'+id).remove();
		for(i=parseInt(id)+1; i<=parseInt(last_id); i++) {
			var j=i-1;
			$('#ticket'+i).attr('data-id', j);
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

