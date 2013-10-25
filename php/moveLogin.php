<?php 
	if (!isset($_SESSION['me'])) {
		unset($_SESSION['nextURL']);
		header('Location: '. SITE_URL. LOGIN_URL);
	}

?>