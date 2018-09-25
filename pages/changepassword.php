<?php
	require_once(__DIR__ . "/../php/register_login.php");
	echo(password_change_login("willi.weissnegger@gmail.com", "password", $_GET["c"]));
?>
