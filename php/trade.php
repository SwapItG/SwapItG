<?php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/session.php");

	//item_offer/item_demand: array(array("name" => "dirt", "count" => "1", "attributes" => array("rare", "item", ...)), array(...), ...)
	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty or the wrong type
	//return 3 -> some strings are is to long
	//return 4 -> not allowed to create new item/attribute for this game
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
			if(empty($item_offer[$i]["name"]) || is_array($item_offer[$i]["name"]) || empty($item_offer[$i]["count"]) || !is_numeric($item_offer[$i]["count"]) || empty($item_offer[$i]["attributes"]) || !is_array($item_offer[$i]["attributes"])) {
				return 2;
			}
			for ($j=0; $j < count($item_offer[$i]["attributes"]); $j++) {
				if(empty($item_offer[$i]["attributes"][$j]) || is_array($item_offer[$i]["attributes"][$j])) {
					return 2;
				}
				if($tmp_game_id != 0) {
					if(create_attribute($item_offer[$i]["attributes"][$j], $tmp_game_id, true) < 0) {
						return 4;
					}
				}
			}
			if($tmp_game_id != 0) {
				if(create_item($item_offer[$i]["name"], $tmp_game_id, true) < 0) {
					return 4;
				}
			}
		}
		for ($i=0; $i < count($item_demand); $i++) {
			if(empty($item_demand[$i]) || !is_array($item_demand[$i])) {
				return 2;
			}
			if(empty($item_demand[$i]["name"]) || is_array($item_demand[$i]["name"]) || empty($item_demand[$i]["count"]) || !is_numeric($item_demand[$i]["count"]) || empty($item_demand[$i]["attributes"]) || !is_array($item_demand[$i]["attributes"])) {
				return 2;
			}
			for ($j=0; $j < count($item_demand[$i]["attributes"]); $j++) {
				if(empty($item_demand[$i]["attributes"][$j]) || is_array($item_demand[$i]["attributes"][$j])) {
					return 2;
				}
				if($tmp_game_id != 0) {
					if(create_attribute($item_demand[$i]["attributes"][$j], $tmp_game_id, true) < 0) {
						return 4;
					}
				}
			}
			if($tmp_game_id != 0) {
				if(create_item($item_demand[$i]["name"], $tmp_game_id, true) < 0) {
					return 4;
				}
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
			$sql = "INSERT INTO item_offer (trade_fk, item_fk, count) VALUES (:trade_id, :item_id, :count)";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->bindParam(":item_id", create_item($item_offer[$i]["name"], $game_id), PDO::PARAM_INT);
			$sth->bindParam(":count", intval($item_offer[$i]["count"]), PDO::PARAM_INT);
			$sth->execute();
			$sql = "SELECT LAST_INSERT_ID() AS id";
			$sth = $pdo->prepare($sql);
			$sth->execute();
			$item_id = $sth->fetch()["id"];
			for ($j=0; $j < count($item_offer[$i]["attributes"]); $j++) {
				$sql = "INSERT INTO item_offer_attribute (item_offer_fk, attribute_fk) VALUES (:item_offer_id, :attribute_id)";
				$sth = $pdo->prepare($sql);
				$sth->bindParam(":item_offer_id", $item_id, PDO::PARAM_INT);
				$sth->bindParam(":attribute_id", create_attribute($item_offer[$i]["attributes"][$j], $game_id), PDO::PARAM_INT);
				$sth->execute();
			}
		}
		for ($i=0; $i < count($item_demand); $i++) {
			$sql = "INSERT INTO item_demand (trade_fk, item_fk, count) VALUES (:trade_id, :item_id, :count)";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->bindParam(":item_id", create_item($item_demand[$i]["name"], $game_id), PDO::PARAM_INT);
			$sth->bindParam(":count", intval($item_demand[$i]["count"]), PDO::PARAM_INT);
			$sth->execute();
			$sql = "SELECT LAST_INSERT_ID() AS id";
			$sth = $pdo->prepare($sql);
			$sth->execute();
			$item_id = $sth->fetch()["id"];
			for ($j=0; $j < count($item_demand[$i]["attributes"]); $j++) {
				$sql = "INSERT INTO item_demand_attribute (item_demand_fk, attribute_fk) VALUES (:item_demand_id, :attribute_id)";
				$sth = $pdo->prepare($sql);
				$sth->bindParam(":item_demand_id", $item_id, PDO::PARAM_INT);
				$sth->bindParam(":attribute_id", create_attribute($item_demand[$i]["attributes"][$j], $game_id), PDO::PARAM_INT);
				$sth->execute();
			}
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
		$sql = "SELECT id FROM game WHERE REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			if($check_only) {
				return 0;
			}
			$sql = "INSERT INTO game (name) VALUES (:name)";
	        $sth = $pdo->prepare($sql);
			$sth->bindParam(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
	        $sth->execute();

			$sql = "SELECT id FROM game WHERE REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
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
		$sql = "SELECT id FROM item WHERE game_fk = :game_id AND REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
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
			$sth->bindParam(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
	        $sth->execute();

			$sql = "SELECT id FROM item WHERE game_fk = :game_id AND REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
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

	//return -1 -> not logedin
	//return -2 -> unexpected error
	//return -3 -> not allowed
	function create_attribute($name, $game_id, $check_only = false) {
		if(!logedin()) {
			return -1;
		}

		global $pdo;
		$sql = "SELECT id FROM attribute WHERE game_fk = :game_id AND REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
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

			$sql = "INSERT INTO attribute (game_fk, name) VALUES (:game_id, :name)";
	        $sth = $pdo->prepare($sql);
			$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindParam(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
	        $sth->execute();

			$sql = "SELECT id FROM attribute WHERE game_fk = :game_id AND REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
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

		$sql = "SELECT id FROM item_offer WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_NUM)) {
			$sql = "DELETE FROM item_offer_attribute WHERE item_offer_fk = :item_offer_id";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindParam(":item_offer_id", $row[0], PDO::PARAM_INT);
			$sth2->execute();
		}
		$sql = "DELETE FROM item_offer WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();

		$sql = "SELECT id FROM item_demand WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		while ($row = $sth->fetch(PDO::FETCH_NUM)) {
			$sql = "DELETE FROM item_demand_attribute WHERE item_demand_fk = :item_demand_id";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindParam(":item_demand_id", $row[0], PDO::PARAM_INT);
			$sth2->execute();
		}
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

	//item_offer/item_demand: array(array("name" => "dirt", "count" => "1", "attributes" => array("rare", "item", ...)), array(...), ...)
	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty or the wrong type
	//return 3 -> some strings are is to long
	//return 4 -> not allowed to create new item/attribute for this game
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
			$sql = "SELECT id FROM item_offer WHERE trade_fk = :trade_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->execute();
			while ($row = $sth->fetch(PDO::FETCH_NUM)) {
				$sql = "DELETE FROM item_offer_attribute WHERE item_offer_fk = :item_offer_id";
				$sth2 = $pdo->prepare($sql);
				$sth2->bindParam(":item_offer_id", $row[0], PDO::PARAM_INT);
				$sth2->execute();
			}
			$sql = "DELETE FROM item_offer WHERE trade_fk = :trade_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->execute();

			$sql = "SELECT id FROM item_demand WHERE trade_fk = :trade_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->execute();
			while ($row = $sth->fetch(PDO::FETCH_NUM)) {
				$sql = "DELETE FROM item_demand_attribute WHERE item_demand_fk = :item_demand_id";
				$sth2 = $pdo->prepare($sql);
				$sth2->bindParam(":item_demand_id", $row[0], PDO::PARAM_INT);
				$sth2->execute();
			}
			$sql = "DELETE FROM item_demand WHERE trade_fk = :trade_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->execute();

			$sql = "DELETE FROM trade_proposal WHERE id = :trade_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
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

		$sql = "SELECT item_offer.id AS item_offer_id, item_fk AS item_id, item.name AS name, count FROM item_offer JOIN item ON item_offer.item_fk = item.id WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		$output["item_offer"] = $sth->fetchAll(PDO::FETCH_ASSOC);

		for ($i=0; $i < count($output["item_offer"]); $i++) {
			$sql = "SELECT attribute_fk AS attribute_id, name FROM item_offer_attribute JOIN attribute ON attribute_fk = id WHERE item_offer_fk = :item_offer_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":item_offer_id", $output["item_offer"][$i]["item_offer_id"], PDO::PARAM_INT);
			$sth->execute();
			$output["item_offer"][$i]["attributes"] = $sth->fetchAll(PDO::FETCH_ASSOC);
		}

		$sql = "SELECT item_demand.id AS item_demand_id, item_fk AS item_id, item.name AS name, count FROM item_demand JOIN item ON item_demand.item_fk = item.id WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		$output["item_demand"] = $sth->fetchAll(PDO::FETCH_ASSOC);

		for ($i=0; $i < count($output["item_demand"]); $i++) {
			$sql = "SELECT attribute_fk AS attribute_id, name FROM item_demand_attribute JOIN attribute ON attribute_fk = id WHERE item_demand_fk = :item_demand_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":item_demand_id", $output["item_demand"][$i]["item_demand_id"], PDO::PARAM_INT);
			$sth->execute();
			$output["item_demand"][$i]["attributes"] = $sth->fetchAll(PDO::FETCH_ASSOC);
		}

		return $output;
	}

	//attributes input: array("name1", "name2", ...)
	//returns array("forward" => "forward_linkdata", "backward" => "backward_linkdata", "list" => array(trade_ids))
	function list_trades($trades_per_page, $linkdata, $game_id, $item_offer_name, $item_demand_name, $item_offer_attributes, $item_demand_attributes) {
		if(empty($item_offer_name) || is_array($item_offer_name)) {
			$item_offer_name = null;
		}
		if(empty($item_demand_name) || is_array($item_demand_name)) {
			$item_demand_name = null;
		}
		if(empty($item_offer_attributes) || !is_array($item_offer_attributes)) {
			$item_offer_attributes = array();
		}
		if(empty($item_demand_attributes) || !is_array($item_demand_attributes)) {
			$item_demand_attributes = array();
		}
		$item_offer_attributes = array_filter($item_offer_attributes, function($element){return !empty($element) && !is_array($element);});
		$item_demand_attributes = array_filter($item_demand_attributes, function($element){return !empty($element) && !is_array($element);});

		$sql_from = "trade_proposal LEFT JOIN item_offer ON trade_proposal.id = item_offer.trade_fk LEFT JOIN item_demand ON trade_proposal.id = item_demand.trade_fk JOIN item AS item_offer_item ON item_offer.item_fk = item_offer_item.id JOIN item AS item_demand_item ON item_demand.item_fk = item_demand_item.id LEFT JOIN item_offer_attribute ON item_offer.id = item_offer_attribute.item_offer_fk LEFT JOIN item_demand_attribute ON item_demand.id = item_demand_attribute.item_demand_fk LEFT JOIN attribute AS offer_attribute ON item_offer_attribute.attribute_fk = offer_attribute.id LEFT JOIN attribute AS demand_attribute ON item_demand_attribute.attribute_fk = demand_attribute.id";
		$sql_where_attribute = "";
		for ($i=0; $i < count($item_offer_attributes); $i++) {
			if($i == 0) {
				$sql_where_attribute .= "AND (";
			}
			$sql_where_attribute .= "REPLACE(offer_attribute.name, \" \", \"\") LIKE REPLACE(:offer_attribute_$i, \" \", \"\")";
			if($i