<?php 
	require_once(dirname(__FILE__). "/php/config.php");
	require_once(dirname(__FILE__). "/php/getLoginInfo.php");
?>


<div id="menu">

	<div id="accountInfo">
		<?php if(isset($_SESSION['me'])): ?>
			<img class="img" src=<?php echo h($_SESSION['me']->profile_image_url); ?> width="40">
			<p><?php echo h($_SESSION['me']->screen_name); ?>さん</p>
		<?php else: ?>
			<p>guestさん</p>
		<?php endif; ?>
		<div id="login">
			<?php if(!isset($_SESSION['me'])): ?>
				<h1><a href="php/login.php"><img src="imgs/login_min_01.gif"></h1></a>
			<?php else: ?>
				<h3><a href="php/logout.php"><img src="imgs/logout_min_01.gif"></h3></a>
			<?php endif; ?>
		</div>

	</div>
	<div id="joinMenu">
		<span class="menuTitle"><img src="imgs/sankasuru01.gif" ></span>
		<ul>
			<li><a href="joinedStamprallys.php">参加中のスタンプラリーを確認する</a></li>
			<li><a href="gotTickets.php">取得済みのチケットを確認する</a></li>
		</ul>
	</div>
	<div id="manageMenu">
		<span class="menuTitle"><img src="imgs/kanrisuru01.gif"></span>
		<ul>
			<li><a href="addStamprally.php">新しくスタンプラリーを登録</a></li>
			<li><a href="managedStamprallys.php">既存のスタンプラリーを管理する</a></li>
		</ul>
	</div>
</div>


