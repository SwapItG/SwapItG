<?php
	require_once(__DIR__ . "/../php/restricted_page.php");
	require_once(__DIR__ . "/../php/session.php");
	setToken();
	print_r($_SESSION);
?>
