<?php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/utility.php");
	require_once(__DIR__ . "/session.php");

	function getEmail() {
		if(logedin()) {
			global $pdo;

			$sql = "SELECT email FROM user WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetch()["email"];
		} else {
			return false;
		}
	}

	function getName() {
		if(logedin()) {
			global $pdo;

			$sql = "SELECT name FROM user WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetch()["name"];
		} else {
			return false;
		}
	}

	function setName($name) {

	}

	function getSteamProfile() {
		if(logedin()) {
			global $pdo;

			$sql = "SELECT steam_profile FROM user WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetch()["steam_profile"];
		} else {
			return false;
		}
	}

	function setSteamProfile($profile_link) {

	}

	function getImage() {
		if(logedin()) {
			global $pdo;

			$sql = "SELECT image FROM user WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return "data:image/jpg;base64, " . base64_encode($sth->fetch()["image"]);
		} else {
			return false;
		}
	}

	function setImage($image) {

	}

	function getInfo() {
		if(logedin()) {
			global $pdo;

			$sql = "SELECT info FROM user WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetch()["info"];
		} else {
			return false;
		}
	}

	function setInfo($info) {

	}
?>
