<?php
	//usage: require or include this inside pages that should be only accessible to logged in users
	require_once(__DIR__ . "/../php/session.php");

	if(!logedin()) {
		header("Location: https://swapitg.com");
		exit;
	}
?>
