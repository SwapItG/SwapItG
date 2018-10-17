<?php
	require_once(__DIR__ . "/php/trade.php");

	$item_offer = array(array("name" => "stone", "count" => "1", "attributes" => array("block")), array("name" => "dirt", "count" => "1", "attributes" => array("common", "block")));
	$item_demand = array(array("name" => "diamond", "count" => "32", "attributes" => array("item", "rare")));
	var_dump(create_trade("test description", "Minecraft", $item_offer, $item_demand));
?>
