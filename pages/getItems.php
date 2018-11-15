<?php
	require_once(__DIR__ . "/../php/db_connect.php");

	if(isset($_GET["game_id"])) {
		//loads all items for a specified game and returns them as an json array
    if (!is_numeric($_GET["game_id"])) {
      $gameID = getGameID($_GET["game_id"]);
    } else {
      $gameID = $_GET["game_id"];
    }
		$sql = "SELECT id, name FROM item WHERE game_fk = :game_id ORDER BY name ASC";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":game_id", $gameID, PDO::PARAM_INT);
		$sth->execute();
		echo(json_encode($sth->fetchAll(PDO::FETCH_ASSOC)));
	}

  function getGameID($gameName) {
      $api_url = 'https://swapitg.com/getGames';
      $json = json_decode(file_get_contents($api_url), true);
      for ($i=0;$i<count($json);$i++) {
        if (strtolower($json[$i]["name"]) == strtolower($gameName)) {
            return $json[$i]["id"];
        }
      }
  }
?>
