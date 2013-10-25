<?php 
	require_once(dirname(__FILE__). "/php/config.php");
	require_once(dirname(__FILE__). "/php/getLoginInfo.php");
?>

<div id="header">
	<div id="title">
		<h1><a href="index.php"><img src="imgs/title01.gif"></a></h1>
	</div>
	<div id="login">
		<?php if(!isset($_SESSION['me'])): ?>
			<h1><a href="php/login.php"><img src="imgs/login_min_01.gif"></h1></a>
		<?php else: ?>
			<h1><a href="php/logout.php">ログアウト</h1></a>
		<?php endif; ?>
	</div>
</div>
