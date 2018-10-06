<?php
	//Worktime: 13:15 - 13:30
	function create_trade($description, $game_name, $item_offer, $item_demand) {

	}

	class ItemStack {
		public $item_name;
		public $amount;
		function __construct($item_name, $amount) {
			$this->item_name = $item_name;
			$this->amount = $amount;
		}
	}
?>
