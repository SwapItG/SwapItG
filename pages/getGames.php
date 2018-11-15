<?php
	require_once(__DIR__ . "/../php/db_connect.php");

	//loads all games and returns them as an json array
	$sql = "SELECT id, name, icon_path FROM game ORDER BY name ASC";
	$sth = $pdo->prepare($sql);
	$sth->execute();
	echo(json_encode($sth->fetchAll(PDO::FETCH_ASSOC)));
?>
