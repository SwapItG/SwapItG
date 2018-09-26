<?php
	require_once(__DIR__ . "/../php/restricted_page.php");
	require_once(__DIR__ . "/../php/userdata_get_set.php");
	require_once(__DIR__ . "/../php/session.php");
	setToken();
	print_r($_SESSION);
	echo("<br />");
	var_dump(getEmail());
	echo("<br />");
	var_dump(getName());
	echo("<br />");
	var_dump(getSteamProfile());
	echo("<br />");
	var_dump(getInfo());
	echo("<br />");
	echo("<img src=\"" . getImage() . "\" />");
?>
