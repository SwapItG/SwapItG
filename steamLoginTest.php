<?php
	require_once(__DIR__ . "/php/steamauth.php");
	require_once(__DIR__ . "/php/session.php");

	if(!logedin()) {
		die("not loged in");
	}
	if(isset($_GET["logout"])) {
		steam_logout();
	}

	if(steam_logedin()) {
		var_dump(get_steam_data());
		echo("<br><a href='?logout'>Logout</a>");
	} else {
		steam_loginbutton();
	}
?>
