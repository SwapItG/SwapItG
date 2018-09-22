<?php
	//usage: <a href="https://swapitg.com/logout/<?php setToken(); echo(getToken()); ... OR
	//usage: <input type="hidden" value="<?php setToken(); echo(getToken()); ...
	require_once(__DIR__ . "/../php/session.php");

	$csrf_token = "";
	if(!empty($_POST["csrf_token"])) {
		$csrf_token = $_POST["csrf_token"];
	} else if(!empty($_GET["csrf_token"])) {
		$csrf_token = $_GET["csrf_token"];
	}

	if(!empty($csrf_token) && validateToken($csrf_token)) {
		session_logout();
	}
?>
