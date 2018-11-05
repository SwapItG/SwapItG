<?php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/session.php");
	require_once(__DIR__ . "/comment_section.php");

	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty or the wrong type
	//return 3 -> some strings are is to long
	//return 4 -> not allowed to create new item/attribute for this game
	//item_offer/item_demand: array(array("name" => "dirt", "count" => "1", "attributes" => array("rare", "item", ...)), array(...), ...)
	function create_trade($description, $game_name, $item_offer, $item_demand, $comment_section_id = -1) {
		//Check Data

		//login check
		if(!logedin()) {
			return 1;
		}
		//empty or array ckeck
		if(is_null($description) || is_array($description) || empty($game_name) || is_array($game_name) || empty($item_offer) || !is_array($item_offer) || empty($item_demand) || !is_array($item_demand)) {
			return 2;
		}
		//check if description or game name are to long
		if(strlen($description) > 512) {
			return 3;
		}
		if(strlen($game_name) > 32) {
			return 3;
		}
		//loop over all item offers
		for ($i=0; $i < count($item_offer); $i++) {
			//empty or array ckecks
			if(empty($item_offer[$i]) || !is_array($item_offer[$i])) {
				return 2;
			}
			if(empty($item_offer[$i]["name"]) || is_array($item_offer[$i]["name"]) || empty($item_offer[$i]["count"]) || !is_numeric($item_offer[$i]["count"]) || is_null($item_offer[$i]["attributes"]) || !is_array($item_offer[$i]["attributes"])) {
				return 2;
			}
			//check if item name is to long
			if(strlen($item_offer[$i]["name"]) > 32) {
				return 3;
			}
			//ensure that an item has not the same attribute multiple times
			$item_offer[$i]["attributes"] = array_unique($item_offer[$i]["attributes"]);
			//loop over all attributes of the item
			for ($j=0; $j < count($item_offer[$i]["attributes"]); $j++) {
				//empty or array ckecks
				if(empty($item_offer[$i]["attributes"][$j]) || is_array($item_offer[$i]["attributes"][$j])) {
					return 2;
				}
				//check if attribute name is to long
				if(strlen($item_offer[$i]["attributes"][$j]) > 32) {
					return 3;
				}
			}
		}
		//loop over all item demands
		for ($i=0; $i < count($item_demand); $i++) {
			//empty or array ckecks
			if(empty($item_demand[$i]) || !is_array($item_demand[$i])) {
				return 2;
			}
			if(empty($item_demand[$i]["name"]) || is_array($item_demand[$i]["name"]) || empty($item_demand[$i]["count"]) || !is_numeric($item_demand[$i]["count"]) || is_null($item_demand[$i]["attributes"]) || !is_array($item_demand[$i]["attributes"])) {
				return 2;
			}
			//check if item name is to long
			if(strlen($item_demand[$i]["name"]) > 32) {
				return 3;
			}
			//ensure that an item has not the same attribute multiple times
			$item_demand[$i]["attributes"] = array_unique($item_demand[$i]["attributes"]);
			//loop over all attributes of the item
			for ($j=0; $j < count($item_demand[$i]["attributes"]); $j++) {
				//empty or array ckecks
				if(empty($item_demand[$i]["attributes"][$j]) || is_array($item_demand[$i]["attributes"][$j])) {
					return 2;
				}
				//check if attribute name is to long
				if(strlen($item_demand[$i]["attributes"][$j]) > 32) {
					return 3;
				}
			}
		}

		//Write Data

		//get game id and create new game if game does not exist yet
		$game_id = create_game($game_name);
		//create new comment section if comment section id is not set
		if($comment_section_id < 1) {
			$comment_section_id = create_comment_section();
		}
		global $pdo;
		//create trade proposal
		$sql = "INSERT INTO trade_proposal (user_fk, description, game_fk, comment_section_fk) VALUES (:user_id, :description, :game_id, :comment_section_id)";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
		$sth->bindValue(":description", htmlspecialchars($description), PDO::PARAM_STR);
		$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->execute();
		//get trade proposal id
		$sql = "SELECT LAST_INSERT_ID() AS id";
		$sth = $pdo->prepare($sql);
		$sth->execute();
		$trade_id = $sth->fetch()["id"];
		//loop over all item overs
		for ($i=0; $i < count($item_offer); $i++) {
			//create item offer
			$sql = "INSERT INTO item_offer (trade_fk, item_fk, count) VALUES (:trade_id, :item_id, :count)";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->bindValue(":item_id", create_item($item_offer[$i]["name"], $game_id), PDO::PARAM_INT);
			$sth->bindParam(":count", intval($item_offer[$i]["count"]), PDO::PARAM_INT);
			$sth->execute();
			//get item over id
			$sql = "SELECT LAST_INSERT_ID() AS id";
			$sth = $pdo->prepare($sql);
			$sth->execute();
			$item_id = $sth->fetch()["id"];
			//loop over all attributes of the item
			for ($j=0; $j < count($item_offer[$i]["attributes"]); $j++) {
				//create item over attribute
				$sql = "INSERT INTO item_offer_attribute (item_offer_fk, attribute_fk) VALUES (:item_offer_id, :attribute_id)";
				$sth = $pdo->prepare($sql);
				$sth->bindParam(":item_offer_id", $item_id, PDO::PARAM_INT);
				$sth->bindValue(":attribute_id", create_attribute($item_offer[$i]["attributes"][$j], $game_id), PDO::PARAM_INT);
				$sth->execute();
			}
		}
		//loop over all item demands
		for ($i=0; $i < count($item_demand); $i++) {
			//create item demand
			$sql = "INSERT INTO item_demand (trade_fk, item_fk, count) VALUES (:trade_id, :item_id, :count)";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
			$sth->bindValue(":item_id", create_item($item_demand[$i]["name"], $game_id), PDO::PARAM_INT);
			$sth->bindParam(":count", intval($item_demand[$i]["count"]), PDO::PARAM_INT);
			$sth->execute();
			//get item demand id
			$sql = "SELECT LAST_INSERT_ID() AS id";
			$sth = $pdo->prepare($sql);
			$sth->execute();
			$item_id = $sth->fetch()["id"];
			//loop over all attributes of the item
			for ($j=0; $j < count($item_demand[$i]["attributes"]); $j++) {
				//create item demand attribute
				$sql = "INSERT INTO item_demand_attribute (item_demand_fk, attribute_fk) VALUES (:item_demand_id, :attribute_id)";
				$sth = $pdo->prepare($sql);
				$sth->bindParam(":item_demand_id", $item_id, PDO::PARAM_INT);
				$sth->bindValue(":attribute_id", create_attribute($item_demand[$i]["attributes"][$j], $game_id), PDO::PARAM_INT);
				$sth->execute();
			}
		}
		return 0;
	}

	//return -1 -> not logedin
	//return -2 -> unexpected error
	function create_game($name) {
		//login check
		if(!logedin()) {
			return -1;
		}

		//get game id if it exists
		global $pdo;
		$sql = "SELECT id FROM game WHERE REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			//create game if it does not exist yet
			$sql = "INSERT INTO game (name) VALUES (:name)";
	        $sth = $pdo->prepare($sql);
			$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
	        $sth->execute();

			//get game id
			$sql = "SELECT id FROM game WHERE REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
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
	function create_item($name, $game_id) {
		//login check
		if(!logedin()) {
			return -1;
		}

		//get item id if it exists
		global $pdo;
		$sql = "SELECT id FROM item WHERE game_fk = :game_id AND REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
		$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			//create item if it does not exist yet
			$sql = "INSERT INTO item (game_fk, name) VALUES (:game_id, :name)";
	        $sth = $pdo->prepare($sql);
			$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
	        $sth->execute();

			//get item id
			$sql = "SELECT id FROM item WHERE game_fk = :game_id AND REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
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
	function create_attribute($name, $game_id) {
		//login check
		if(!logedin()) {
			return -1;
		}

		//get attribute id if it exists
		global $pdo;
		$sql = "SELECT id FROM attribute WHERE game_fk = :game_id AND REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
		$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			//create attribute if it does not exist yet
			$sql = "INSERT INTO attribute (game_fk, name) VALUES (:game_id, :name)";
	        $sth = $pdo->prepare($sql);
			$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
	        $sth->execute();

			//get attribute id
			$sql = "SELECT id FROM attribute WHERE game_fk = :game_id AND REPLACE(name, \" \", \"\") = REPLACE(:name, \" \", \"\")";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindValue(":name", trim(htmlspecialchars($name)), PDO::PARAM_STR);
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
	function delete_trade($trade_id, $keep_comment_section = false) {
		//login check
		if(!logedin()) {
			return 1;
		}

		//check if trade exists
		global $pdo;
		$sql = "SELECT comment_section_fk FROM trade_proposal WHERE id = :id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $trade_id, PDO::PARAM_INT);
		$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 2;
		}

		//if comment section should deleted delete it
		if(!$keep_comment_section) {
			delete_comment_section($sth->fetch()["comment_section_fk"]);
		}

		//get all item offer ids
		$sql = "SELECT id FROM item_offer WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		//loop over all item offer ids
		while ($row = $sth->fetch(PDO::FETCH_NUM)) {
			//delete all item attributes
			$sql = "DELETE FROM item_offer_attribute WHERE item_offer_fk = :item_offer_id";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindParam(":item_offer_id", $row[0], PDO::PARAM_INT);
			$sth2->execute();
		}
		//delete all item offers
		$sql = "DELETE FROM item_offer WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();

		//get all item demand ids
		$sql = "SELECT id FROM item_demand WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		//loop over all item demand ids
		while ($row = $sth->fetch(PDO::FETCH_NUM)) {
			//delete all item attributes
			$sql = "DELETE FROM item_demand_attribute WHERE item_demand_fk = :item_demand_id";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindParam(":item_demand_id", $row[0], PDO::PARAM_INT);
			$sth2->execute();
		}
		//delete all item demands
		$sql = "DELETE FROM item_demand WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();

		//delete trade proposal
		$sql = "DELETE FROM trade_proposal WHERE id = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		return 0;
	}

	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty or the wrong type
	//return 3 -> some strings are is to long
	//return 4 -> not allowed to create new item/attribute for this game
	//return 5 -> trade not found
	//item_offer/item_demand: array(array("name" => "dirt", "count" => "1", "attributes" => array("rare", "item", ...)), array(...), ...)
	function edit_trade($old_trade_id, $description, $game_name, $item_offer, $item_demand) {
		//check if logedin
		if(!logedin()) {
			return 1;
		}

		//check if trade exists
		global $pdo;
		$sql = "SELECT comment_section_fk FROM trade_proposal WHERE id = :id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $old_trade_id, PDO::PARAM_INT);
		$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 5;
		}

		//create trade with given parameters and the old comment section id
		$error_code = create_trade($description, $game_name, $item_offer, $item_demand, $sth->fetch()["comment_section_fk"]);
		if($error_code == 0) {
			//delete old trade trade without deleting the comment section
			delete_trade($old_trade_id, true);
			return 0;
		} else {
			return $error_code;
		}
	}

	//get game name of the game with the given id
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

	//get game icon of the game with the given id
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

	//get item name of the item with the given id
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

	//get comment section id from the trade with the given id
	function getTradeCommentSection($trade_id) {
		global $pdo;
		$sql = "SELECT comment_section_fk FROM trade_proposal WHERE id = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return false;
		}

		return $sth->fetch()["comment_section_fk"];
	}

	function getTradeData($trade_id) {
		$output = array();
		global $pdo;

		//get info about the trade
		$sql = "SELECT user_fk AS user_id, description, creation_time, game_fk AS game_id, game.name AS game_name, game.icon_path AS icon_path, comment_section_fk AS comment_section_id FROM trade_proposal JOIN game ON game.id = trade_proposal.game_fk WHERE trade_proposal.id = :id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		if($sth->rowCount() == 0) {
			return false;
		}
		//store info in output array
		$output = array_merge($output, $sth->fetch(PDO::FETCH_ASSOC));

		//get info about the item offer
		$sql = "SELECT item_offer.id AS item_offer_id, item_fk AS item_id, item.name AS name, count FROM item_offer JOIN item ON item_offer.item_fk = item.id WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		//store info in output array
		$output["item_offer"] = $sth->fetchAll(PDO::FETCH_ASSOC);

		for ($i=0; $i < count($output["item_offer"]); $i++) {
			//get info about the item offer attributes
			$sql = "SELECT attribute_fk AS attribute_id, name FROM item_offer_attribute JOIN attribute ON attribute_fk = id WHERE item_offer_fk = :item_offer_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":item_offer_id", $output["item_offer"][$i]["item_offer_id"], PDO::PARAM_INT);
			$sth->execute();
			//store info in output array
			$output["item_offer"][$i]["attributes"] = $sth->fetchAll(PDO::FETCH_ASSOC);
		}

		//get info about the item demand
		$sql = "SELECT item_demand.id AS item_demand_id, item_fk AS item_id, item.name AS name, count FROM item_demand JOIN item ON item_demand.item_fk = item.id WHERE trade_fk = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		//store info in output array
		$output["item_demand"] = $sth->fetchAll(PDO::FETCH_ASSOC);

		for ($i=0; $i < count($output["item_demand"]); $i++) {
			//get info about the item demand attributes
			$sql = "SELECT attribute_fk AS attribute_id, name FROM item_demand_attribute JOIN attribute ON attribute_fk = id WHERE item_demand_fk = :item_demand_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":item_demand_id", $output["item_demand"][$i]["item_demand_id"], PDO::PARAM_INT);
			$sth->execute();
			//store info in output array
			$output["item_demand"][$i]["attributes"] = $sth->fetchAll(PDO::FETCH_ASSOC);
		}

		return $output;
	}

	//attributes input: array("name1", "name2", ...)
	//returns array("forward" => "forward_linkdata", "backward" => "backward_linkdata", "list" => array(trade_ids))
	function list_trades($trades_per_page, $linkdata, $game_id, $item_offer_name, $item_demand_name, $item_offer_attributes, $item_demand_attributes) {
		//set item offer name to null if it is empty or an array
		if(empty($item_offer_name) || is_array($item_offer_name)) {
			$item_offer_name = null;
		}
		//set item demand name to null if it is empty or an array
		if(empty($item_demand_name) || is_array($item_demand_name)) {
			$item_demand_name = null;
		}
		//set item offer attributes to array if it is empty or not an array
		if(empty($item_offer_attributes) || !is_array($item_offer_attributes)) {
			$item_offer_attributes = array();
		}
		//set item demand attributes to array if it is empty or not an array
		if(empty($item_demand_attributes) || !is_array($item_demand_attributes)) {
			$item_demand_attributes = array();
		}
		//remove empty or array values inside the attributs
		$item_offer_attributes = array_filter($item_offer_attributes, function($element){return !empty($element) && !is_array($element);});
		$item_demand_attributes = array_filter($item_demand_attributes, function($element){return !empty($element) && !is_array($element);});

		//the from part of the sql query
		$sql_from = "trade_proposal LEFT JOIN item_offer ON trade_proposal.id = item_offer.trade_fk LEFT JOIN item_demand ON trade_proposal.id = item_demand.trade_fk JOIN item AS item_offer_item ON item_offer.item_fk = item_offer_item.id JOIN item AS item_demand_item ON item_demand.item_fk = item_demand_item.id LEFT JOIN item_offer_attribute ON item_offer.id = item_offer_attribute.item_offer_fk LEFT JOIN item_demand_attribute ON item_demand.id = item_demand_attribute.item_demand_fk LEFT JOIN attribute AS offer_attribute ON item_offer_attribute.attribute_fk = offer_attribute.id LEFT JOIN attribute AS demand_attribute ON item_demand_attribute.attribute_fk = demand_attribute.id";
		//generate a part of the where part of the sql query
		$sql_where_attribute = "";
		for ($i=0; $i < count($item_offer_attributes); $i++) {
			if($i == 0) {
				$sql_where_attribute .= "AND (";
			}
			$sql_where_attribute .= "REPLACE(offer_attribute.name, \" \", \"\") LIKE REPLACE(:offer_attribute_$i, \" \", \"\")";
			if($i < count($item_offer_attributes) - 1) {
				$sql_where_attribute .= " OR ";
			} else {
				$sql_where_attribute .= ") ";
			}
		}
		for ($i=0; $i < count($item_demand_attributes); $i++) {
			if($i == 0) {
				$sql_where_attribute .= "AND (";
			}
			$sql_where_attribute .= "REPLACE(demand_attribute.name, \" \", \"\") LIKE REPLACE(:demand_attribute_$i, \" \", \"\")";
			if($i < count($item_demand_attributes) - 1) {
				$sql_where_attribute .= " OR ";
			} else {
				$sql_where_attribute .= ") ";
			}
		}
		//the where part of the sql query
		$sql_where = "AND (:item_offer_name IS NULL OR REPLACE(item_offer_item.name, \" \", \"\") LIKE REPLACE(:item_offer_name, \" \", \"\")) AND (:item_demand_name IS NULL OR REPLACE(item_demand_item.name, \" \", \"\") LIKE REPLACE(:item_demand_name, \" \", \"\")) $sql_where_attribute";
		//the having part of the sql query
		$sql_having = "COUNT(DISTINCT item_offer_attribute.attribute_fk) >= :offer_attribut_count AND COUNT(DISTINCT item_demand_attribute.attribute_fk) >= :demand_attribut_count";

		global $pdo;
		if($linkdata == 0) {
			//get first page
			$sql = "SELECT DISTINCT trade_proposal.id FROM $sql_from WHERE (:game_id IS NULL OR trade_proposal.game_fk = :game_id) $sql_where GROUP BY item_offer.id, item_demand.id HAVING $sql_having ORDER BY trade_proposal.id DESC LIMIT :amount";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindValue(":item_offer_name", "%" . htmlspecialchars($item_offer_name) . "%", PDO::PARAM_STR);
			$sth->bindValue(":item_demand_name", "%" . htmlspecialchars($item_demand_name) . "%", PDO::PARAM_STR);
			$sth->bindValue(":amount", $trades_per_page + 1, PDO::PARAM_INT);
			generate_bind_attribute($sth, $item_offer_attributes, $item_demand_attributes);
			$sth->execute();
			$output = $sth->fetchAll(PDO::FETCH_COLUMN);

			//check if there is an next page
			$forward_linkdata = (count($output) > $trades_per_page) ? $output[$trades_per_page - 1] : false;
			$backward_linkdata = false;
			return array("forward" => $forward_linkdata, "backward" => $backward_linkdata, "list" => array_slice($output, 0, $trades_per_page));
		} else if($linkdata > 0) {
			//get next page
			$sql = "SELECT DISTINCT trade_proposal.id FROM $sql_from WHERE trade_proposal.id < :linkdata AND (:game_id IS NULL OR trade_proposal.game_fk = :game_id) $sql_where GROUP BY item_offer.id, item_demand.id HAVING $sql_having ORDER BY trade_proposal.id DESC LIMIT :amount";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":linkdata", $linkdata, PDO::PARAM_INT);
			$sth->bindValue(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindValue(":item_offer_name", "%" . htmlspecialchars($item_offer_name) . "%", PDO::PARAM_STR);
			$sth->bindValue(":item_demand_name", "%" . htmlspecialchars($item_demand_name) . "%", PDO::PARAM_STR);
			$sth->bindValue(":amount", $trades_per_page + 1, PDO::PARAM_INT);
			generate_bind_attribute($sth, $item_offer_attributes, $item_demand_attributes);
			$sth->execute();
			$output = $sth->fetchAll(PDO::FETCH_COLUMN);

			//check if there is an next page
			$forward_linkdata = (count($output) > $trades_per_page) ? $output[$trades_per_page - 1] : false;

			$sql = "SELECT DISTINCT trade_proposal.id FROM $sql_from WHERE trade_proposal.id >= :linkdata AND (:game_id IS NULL OR trade_proposal.game_fk = :game_id) $sql_where GROUP BY item_offer.id, item_demand.id HAVING $sql_having ORDER BY trade_proposal.id ASC LIMIT :amount";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindValue(":linkdata", $linkdata, PDO::PARAM_INT);
			$sth2->bindValue(":game_id", $game_id, PDO::PARAM_INT);
			$sth2->bindValue(":item_offer_name", "%" . htmlspecialchars($item_offer_name) . "%", PDO::PARAM_STR);
			$sth2->bindValue(":item_demand_name", "%" . htmlspecialchars($item_demand_name) . "%", PDO::PARAM_STR);
			$sth2->bindValue(":amount", $trades_per_page + 1, PDO::PARAM_INT);
			generate_bind_attribute($sth2, $item_offer_attributes, $item_demand_attributes);
			$sth2->execute();

			//check if there is an previous page
			$backward_linkdata = (count($sth2->fetchAll(PDO::FETCH_NUM)) > $trades_per_page) ? $output[0] : 0;
			return array("forward" => $forward_linkdata, "backward" => -$backward_linkdata, "list" => array_slice($output, 0, $trades_per_page));
		} else {
			//get previous page
			$sql = "SELECT DISTINCT trade_proposal.id FROM $sql_from WHERE trade_proposal.id > :linkdata AND (:game_id IS NULL OR trade_proposal.game_fk = :game_id) $sql_where GROUP BY item_offer.id, item_demand.id HAVING $sql_having ORDER BY trade_proposal.id ASC LIMIT :amount";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":linkdata", -$linkdata, PDO::PARAM_INT);
			$sth->bindValue(":game_id", $game_id, PDO::PARAM_INT);
			$sth->bindValue(":item_offer_name", "%" . htmlspecialchars($item_offer_name) . "%", PDO::PARAM_STR);
			$sth->bindValue(":item_demand_name", "%" . htmlspecialchars($item_demand_name) . "%", PDO::PARAM_STR);
			$sth->bindValue(":amount", $trades_per_page * 2 + 1, PDO::PARAM_INT);
			generate_bind_attribute($sth, $item_offer_attributes, $item_demand_attributes);
			$sth->execute();

			$output = $sth->fetchAll(PDO::FETCH_COLUMN);
			$forward_linkdata = $output[0];
			//check if there is an previous page
			$backward_linkdata = (count($output) > ($trades_per_page * 2)) ? $output[$trades_per_page - 1] : 0;
			return array("forward" => $forward_linkdata, "backward" => -$backward_linkdata, "list" => array_reverse(array_slice($output, 0, $trades_per_page)));
		}
	}

	//bind attributes
	function generate_bind_attribute(&$sth, $item_offer_attributes, $item_demand_attributes) {
		for ($i=0; $i < count($item_offer_attributes); $i++) {
			$sth->bindValue(":offer_attribute_$i", "%" . htmlspecialchars($item_offer_attributes[$i]) . "%", PDO::PARAM_STR);
		}
		for ($i=0; $i < count($item_demand_attributes); $i++) {
			$sth->bindValue(":demand_attribute_$i", "%" . htmlspecialchars($item_demand_attributes[$i]) . "%", PDO::PARAM_STR);
		}
		$sth->bindValue(":offer_attribut_count", count($item_offer_attributes), PDO::PARAM_INT);
		$sth->bindValue(":demand_attribut_count", count($item_demand_attributes), PDO::PARAM_INT);
	}

	//get comment section status of trade
	function getTradeCommentSectionStatus($trade_id) {
		global $pdo;
		$sql = "SELECT comment_section_fk FROM trade_proposal WHERE id = :trade_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->execute();
		if($sth->rowCount() != 0) {
			return get_status_comment_section($sth->fetch()["comment_section_fk"]);
		} else {
			return false;
		}
	}

	//set comment section status of trade
	function setTradeCommentSectionStatus($trade_id, $status) {
		if(!logedin()) {
			return false;
		}

		global $pdo;
		$sql = "SELECT comment_section_fk FROM trade_proposal WHERE id = :trade_id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":trade_id", $trade_id, PDO::PARAM_INT);
		$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();
		if($sth->rowCount() != 0) {
			set_status_comment_section($sth->fetch()["comment_section_fk"], $status);
			return true;
		} else {
			return false;
		}
	}

	//check if owner of trade
	function owner_of_trade($trade_id) {
		if(!logedin()) {
			return false;
		}

		global $pdo;
		$sql = "SELECT id FROM trade_proposal WHERE id = :id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $trade_id, PDO::PARAM_INT);
		$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return false;
		}
		return true;
	}
?>
