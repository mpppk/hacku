<?php
	if ($_SESSION['me'] != NULL) {
		$userInfo['isLogin'] = true;
		$userInfo['id'] = $_SESSION['me']->id;
		$userInfo['screenName'] = $_SESSION['me']->screen_name;
		$userInfo['imgURL'] = $_SESSION['me']->profile_image_url;
	}else{
		$userInfo['isLogin'] = false;
		$userInfo['screenName'] = "guest";
	}
?>
