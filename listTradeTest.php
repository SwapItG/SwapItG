<?php
	require_once(__DIR__ . "/php/trade.php");
	require_once(__DIR__ . "/php/session.php");

	$output = array();
	if(isset($_POST["pagelink"])) {
		$output = list_trades(3, $_POST["pagelink"], $_GET["game_id"], $_GET["offer"], $_GET["demand"], explode(", ", $_GET["offer_attributes"]), explode(", ", $_GET["demand_attributes"]));
	} else {
		$output = list_trades(3, 0, $_GET["game_id"], $_GET["offer"], $_GET["demand"], explode(", ", $_GET["offer_attributes"]), explode(", ", $_GET["demand_attributes"]));
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>listTradeTest</title>
	</head>
	<body>
		<form action="" method="get">
			Game Id:
			<input type="number" name="game_id" value="<?php echo($_GET["game_id"]) ?>">
			Search Offer:
			<input type="text" name="offer" value="<?php echo($_GET["offer"]) ?>">
			Search Demand:
			<input type="text" name="demand" value="<?php echo($_GET["demand"]) ?>">
			Offer Attributes (, seperated):
			<input type="text" name="offer_attributes" value="<?php echo($_GET["offer_attributes"]) ?>">
			Demand Attributes (, seperated):
			<input type="text" name="demand_attributes" value="<?php echo($_GET["demand_attributes"]) ?>">
			<input type="submit" value="Search">
		</form>
		<form action="" method="post">
			First Page:
			<button type="submit" name="pagelink" <?php echo($output["backward"] !== false ? "" : "disabled") ?> value="0">|&#x3C;</button>
			Backward:
			<button type="submit" name="pagelink" <?php echo($output["backward"] !== false ? "" : "disabled") ?> value="<?php echo($output["backward"]) ?>">&#x3C;&#x3C;</button>
			Forward:
			<button type="submit" name="pagelink" <?php echo($output["forward"] !== false ? "" : "disabled") ?> value="<?php echo($output["forward"]) ?>">&#x3E;&#x3E;</button>
		</form>
		<br>
		<br>
		<pre>
			<?php
				for ($i=0; $i < count($output["list"]); $i++) {
					print_r(getTradeData($output["list"][$i]));
				}
			?>
		</pre>
	</body>
</html>
