<?php
	require_once(__DIR__ . "/php/trade.php");

	$item_offer = array(array("name" => "stone", "count" => "1", "info" => "very rare"));
	$item_demand = array(array("name" => "diamond", "count" => "32", "info" => "common"));
	var_dump(create_trade("test description", "Minecraft", $item_offer, $item_demand));
?>
