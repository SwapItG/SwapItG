<?php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/session.php");

	//item_offer/item_demand: array(array("name" => "dirt", "count" => "1", "info" => "block rare unique"), array(...), ...)
	function create_trade($description, $game_name, $item_offer, $item_demand) {
		if(!logedin()) {
			return false;
		}
		if(is_null($description) || is_array($description) || empty($game_name) || is_array($game_name) || empty($item_offer) || !is_array($item_offer) || empty($item_demand) || !is_array($item_demand)) {
			return false;
		}
		if(strlen($description) > 512) {
			return false;
		}
		$tmp_game_id = create_game($game_name, true);
		for ($i=0; $i < count($item_offer); $i++) {
			if(empty($item_offer[$i]) || !is_array($item_offer[$i])) {
				return false;
			}
			if(empty($item_offer[$i]["name"]) || is_array($item_offer[$i]["name"]) || empty($item_offer[$i]["count"]) || is_array($item_offer[$i]["count"]) || is_null($item_offer[$i]["info"]) || is_array($item_offer[$i]["info"])) {
				return false;
			}
			if($tmp_game_id != 0) {
				if(create_item($item_offer[$i]["name"], $tmp_game_id, true) < 0) {
					return false;
				}
			}
			if(strlen($item_offer[$i]["info"]) > 512) {
				return false;
			}
		}
		for ($i=0; $i < count($item_demand); $i++) {
			if(empty($item_demand[$i]) || !is_array($item_demand[$i])) {
				return false;
			}
			if(empty($item_demand[$i]["name"]) || is_array($item_demand[$i]["name"]) || empty($item_demand[$i]["count"]) || is_array($item_demand[$i]["count"]) || is_null($item_demand[$i]["info"]) || is_array($item_demand[$i]["info"])) {
				return false;
			}
			if($tmp_game_id != 0) {
				if(create_item($item_demand[$i]["name"], $tmp_game_id, true) < 0) {
					return false;
				}
			}
			if(strlen($item_demand[$i]["info"]) > 512) {
				return false;
			}
		}
		//TODO: write data
		//TODO: Test
	}

	//return -1 -> not logedin
	//return -2 -> unexpected error
	function create_game($name, $check_only = false) {
		if(!logedin()) {
			return -1;
		}

		global $pdo;
		$sql = "SELECT id FROM game WHERE name = :name";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			if($check_only) {
				return 0;
			}
			$sql = "INSERT INTO game (name) VALUES (:name)";
	        $sth = $pdo->prepare($sql);
			$sth->bindParam(":name", htmlspecialchars($name), PDO::PARAM_STR);
	        $sth->execute();

			$sql = "SELECT id FROM game WHERE name = :name";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
			$sth->execute();

			if($sth->rowCount() == 0) {
				return -2;
			}
			return $sth->fetch()["id"];
		} else {
			return $sth->fetch()["id"];
		}
	}

	//return -1 -> not logedin
	//return -2 -> unexpected error
	//return -3 -> not allowed
	function create_item($name, $game_id, $check_only = false) {
		if(!logedin()) {
			return -1;
		}

		global $pdo;
		$sql = "SELECT id FROM item WHERE game_fk = :game_id AND name = :name";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
		$sth->bindParam(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			$sql = "SELECT icon_path FROM game WHERE id = :game_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
			$sth->execute();

			if($sth->rowCount() != 0 && !empty($sth->fetch()["icon_path"])) {
				return -3;
			}

			if($check_only) {
				return 0;
			}

			$sql = "INSERT INTO item (game_fk, name) VALUES (:game_id, :name)";
	        $sth = $pdo->prepare($sql);
			$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindParam(":name", htmlspecialchars($name), PDO::PARAM_STR);
	        $sth->execute();

			$sql = "SELECT id FROM item WHERE game_fk = :game_id AND name = :name";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindParam(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
			$sth->execute();

			if($sth->rowCount() == 0) {
				return -2;
			}
			return $sth->fetch()["id"];
		} else {
			return $sth->fetch()["id"];
		}
	}
?>
