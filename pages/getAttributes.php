<?php
	require_once(__DIR__ . "/../php/db_connect.php");
	$sql = "SELECT id, name FROM attribute WHERE game_fk = :game_id ORDER BY name ASC";
	$sth = $pdo->prepare($sql);
	$sth->bindParam(":game_id", $_GET["game_id"], PDO::PARAM_INT);
	$sth->execute();
	echo(json_encode($sth->fetchAll(PDO::FETCH_ASSOC)));
?>
