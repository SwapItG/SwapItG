<?php
	require_once(__DIR__ . "/php/trade.php");

	$output = array();
	if(isset($_POST["pagelink"])) {
		$output = list_trades(3, $_POST["pagelink"]);
	} else {
		$output = list_trades(3, 0);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>listTradeTest</title>
	</head>
	<body>
		<form action="" method="post">
			First Page:
			<button type="submit" name="pagelink" <?php echo($output["backward"] !== false ? "" : "disabled") ?> value="0">|&#x3C;</button>
			Backward:
			<button type="submit" name="pagelink" <?php echo($output["backward"] !== false ? "" : "disabled") ?> value="<?php echo($output["backward"]) ?>">&#x3C;&#x3C;</button>
			Forward:
			<button type="submit" name="pagelink" <?php echo($output["forward"] !== false ? "" : "disabled") ?> value="<?php echo($output["forward"]) ?>">&#x3E;&#x3E;</button>
			<br>
			<br>
			<?php print_r($output["list"]) ?>
		</form>
	</body>
</html>
