<?php
	require_once(__DIR__ . "/php/trade.php");

	$item_offer = array(array("name" => "stone", "count" => "1", "attributes" => array("block")), array("name" => "dirt", "count" => "1", "attributes" => array("common", "block")));
	$item_demand = array(array("name" => "diamond", "count" => "32", "attributes" => array("item", "rare")));
	echo "<pre>";
	print_r($item_offer);
	print_r($item_demand);
	echo "</pre>";
	echo create_trade("test description", "Diablo 3", $item_offer, $item_demand);
?>
