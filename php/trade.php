<?php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/session.php");

	//item_offer/item_demand: array(array("name" => "dirt", "count" => "1", "info" => "block rare unique"), array(...), ...)
	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty or the wrong type
	//return 3 -> some strings are to long
	//return 4 -> not allowed to create new item for this game
	function create_trade($description, $game_name, $item_offer, $item_demand) {
		//Check Data
		if(!logedin()) {
			return 1;
		}
		if(is_null($description) || is_array($description) || empty($game_name) || is_array($game_name) || empty($item_offer) || !is_array($item_offer) || empty($item_demand) || !is_array($item_demand)) {
			return 2;
		}
		if(strlen($description) > 512) {
			return 3;
		}
		$tmp_game_id = create_game($game_name, true);
		for ($i=0; $i < count($item_offer); $i++) {
			if(empty($item_offer[$i]) || !is_array($item_offer[$i])) {
				return 2;
			}
			if(empty($item_offer[$i]["name"]) || is_array($item_offer[$i]["name"]) || empty($item_offer[$i]["count"]) || !is_numeric($item_offer[$i]["count"]) || is_null($item_offer[$i]["info"]) || is_array($item_offer[$i]["info"])) {
				return 2;
			}
			if($tmp_game_id != 0) {
				if(create_item($item_offer[$i]["name"], $tmp_game_id, true) < 0) {
					return 4;
				}
			}
			if(strlen($item_offer[$i]["info"]) > 512) {
				return 3;
			}
		}
		for ($i=0; $i < count($item_demand); $i++) {
			if(empty($item_demand[$i]) || !is_array($item_demand[$i])) {
				return 2;
			}
			if(empty($item_demand[$i]["name"]) || is_array($item_demand[$i]["name"]) || empty($item_demand[$i]["count"]) || !is_numeric($item_demand[$i]["count"]) || is_null($item_demand[$i]["info"]) || is_array($item_demand[$i]["info"])) {
				return 2;
			}
			if($tmp_game_id != 0) {
				if(create_item($item_demand[$i]["name"], $tmp_game_id, true) < 0) {
					return 4;
				}
			}
			if(strlen($item_demand[$i]["info"]) > 512) {
				return 3;
			}
		}

		//Write Data
		$game_id = create_game($game_name);
		global $pdo;
		$sql = "INSERT INTO trade_proposal (user_fk, description, game_fk) VALUES (:user_id, :description, :game_id)";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":user_id", logedin(), PDO::PARAM_INT);
		$sth->bindParam(":description", htmlspecialchars($description), PDO::PARAM_STR);
		$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
		$sth->execute();
		$sql = "SELECT LAST_INSERT_ID() AS id";
		$sth = $pdo->prepare($sql);
		$sth->execute();
		$trade_id = $sth->fetch()["id"];
		for ($i=0; $i < count($item_offer); $i++) {
			$sql = "INSERT INTO item_offer (trade_fk, item_fk, count, info) VALUES (:trade_id, :item_id, :count, :info)";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->bindParam(":item_id", create_item($item_offer[$i]["name"], $game_id), PDO::PARAM_INT);
			$sth->bindParam(":count", intval($item_offer[$i]["count"]), PDO::PARAM_INT);
			$sth->bindParam(":info", $item_offer[$i]["info"], PDO::PARAM_STR);
			$sth->execute();
		}
		for ($i=0; $i < count($item_demand); $i++) {
			$sql = "INSERT INTO item_demand (trade_fk, item_fk, count, info) VALUES (:trade_id, :item_id, :count, :info)";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->bindParam(":item_id", create_item($item_demand[$i]["name"], $game_id), PDO::PARAM_INT);
			$sth->bindParam(":count", intval($item_demand[$i]["count"]), PDO::PARAM_INT);
			$sth->bindParam(":info", $item_demand[$i]["info"], PDO::PARAM_STR);
			$sth->execute();
		}
		return 0;
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

	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> trade not found
	function delete_trade($trade_id) {
		if(!logedin()) {
			return 1;
		}

		global $pdo;
		$sql = "SELECT id FROM trade_proposal WHERE id = :id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $trade_id, PDO::PARAM_INT);
		$sth->bindParam(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 2;
		}

		$sql = "DELETE FROM item_offer WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		$sql = "DELETE FROM item_demand WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		$sql = "DELETE FROM trade_proposal WHERE id = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		return 0;
	}

	function getTrades($user_id = -1) {
		if(logedin() || $user_id != -1) {
			global $pdo;
			$sql = "SELECT id FROM trade_proposal WHERE user_fk = :user_id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":user_id", ($user_id == -1) ? logedin() : $user_id, PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_COLUMN);
		} else {
			return false;
		}
	}

	//item_offer/item_demand: array(array("name" => "dirt", "count" => "1", "info" => "block rare unique"), array(...), ...)
	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty or the wrong type
	//return 3 -> some strings are to long
	//return 4 -> not allowed to create new item for this game
	//return 5 -> trade not found
	function edit_trade($old_trade_id, $description, $game_name, $item_offer, $item_demand) {
		if(!logedin()) {
			return 1;
		}

		global $pdo;
		$sql = "SELECT id FROM trade_proposal WHERE id = :id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $old_trade_id, PDO::PARAM_INT);
		$sth->bindParam(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 5;
		}

		$error_code = create_trade($description, $game_name, $item_offer, $item_demand);
		if($error_code == 0) {
			$sql = "DELETE FROM item_offer WHERE trade_fk = :trade_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $old_trade_id, PDO::PARAM_INT);
			$sth->execute();
			$sql = "DELETE FROM item_demand WHERE trade_fk = :trade_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $old_trade_id, PDO::PARAM_INT);
			$sth->execute();
			$sql = "DELETE FROM trade_proposal WHERE id = :trade_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $old_trade_id, PDO::PARAM_INT);
			$sth->execute();
			return 0;
		} else {
			return $error_code;
		}
	}

	function getGameName($game_id) {
		global $pdo;
		$sql = "SELECT name FROM game WHERE id = :game_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return false;
		}

		return $sth->fetch()["name"];
	}

	function getGameIcon($game_id) {
		global $pdo;
		$sql = "SELECT icon_path FROM game WHERE id = :game_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return false;
		}

		return $sth->fetch()["icon_path"];
	}

	function getItemName($item_id) {
		global $pdo;
		$sql = "SELECT name FROM item WHERE id = :item_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":item_id", $item_id, PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return false;
		}

		return $sth->fetch()["name"];
	}

	function getTradeData($trade_id) {
		$output = array();
		global $pdo;

		$sql = "SELECT user_fk AS user_id, description, creation_time, game_fk AS game_id, game.name AS game_name, game.icon_path AS icon_path FROM trade_proposal JOIN game ON game.id = trade_proposal.game_fk WHERE trade_proposal.id = :id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		if($sth->rowCount() == 0) {
			return false;
		}
		$output = array_merge($output, $sth->fetch(PDO::FETCH_ASSOC));

		$sql = "SELECT item_fk AS item_id, item.name AS name, count, info FROM item_offer JOIN item ON item_offer.item_fk = item.id WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		$output["item_offer"] = $sth->fetchAll(PDO::FETCH_ASSOC);

		$sql = "SELECT item_fk AS item_id, item.name AS name, count, info FROM item_demand JOIN item ON item_demand.item_fk = item.id WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		$output["item_demand"] = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $output;
	}

	//TODO search parameter
	// returns array("forward" => "forward_linkdata", "backward" => "backward_linkdata", "list" => array(trade_ids))
	function list_trades($trades_per_page, $linkdata) {
		global $pdo;
		if($linkdata == 0) {
			$sql = "SELECT id FROM trade_proposal ORDER BY id DESC LIMIT :amount";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":amount", $trades_per_page + 1, PDO::PARAM_INT);
			$sth->execute();
			$output = $sth->fetchAll(PDO::FETCH_COLUMN);

			$forward_linkdata = (count($output) > $trades_per_page) ? $output[$trades_per_page - 1] : false;
			$backward_linkdata = false;
			return array("forward" => $forward_linkdata, "backward" => $backward_linkdata, "list" => array_slice($output, 0, $trades_per_page));
		} else if($linkdata > 0) {
			$sql = "SELECT id FROM trade_proposal WHERE id < :linkdata ORDER BY id DESC LIMIT :amount";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":linkdata", $linkdata, PDO::PARAM_INT);
			$sth->bindValue(":amount", $trades_per_page + 1, PDO::PARAM_INT);
			$sth->execute();
			$output = $sth->fetchAll(PDO::FETCH_COLUMN);

			$forward_linkdata = (count($output) > $trades_per_page) ? $output[$trades_per_page - 1] : false;
			$sql = "SELECT id FROM trade_proposal WHERE id >= :linkdata ORDER BY id ASC LIMIT :amount";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindValue(":linkdata", $linkdata, PDO::PARAM_INT);
			$sth2->bindValue(":amount", $trades_per_page + 1, PDO::PARAM_INT);
			$sth2->execute();
			$backward_linkdata = (count($sth2->fetchAll(PDO::FETCH_NUM)) > $trades_per_page) ? $output[0] : 0;
			return array("forward" => $forward_linkdata, "backward" => -$backward_linkdata, "list" => array_slice($output, 0, $trades_per_page));
		} else {
			$sql = "SELECT id FROM trade_proposal WHERE id > :linkdata ORDER BY id ASC LIMIT :amount";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":linkdata", -$linkdata, PDO::PARAM_INT);
			$sth->bindValue(":amount", $trades_per_page * 2 + 1, PDO::PARAM_INT);
			$sth->execute();
			$output = $sth->fetchAll(PDO::FETCH_COLUMN);
			$forward_linkdata = $output[0];
			$backward_linkdata = (count($output) > ($trades_per_page * 2)) ? $output[$trades_per_page - 1] : 0;
			return array("forward" => $forward_linkdata, "backward" => -$backward_linkdata, "list" => array_reverse(array_slice($output, 0, $trades_per_page)));
		}
	}
?>
