<?php
	require_once(__DIR__ . "/php/comment_section.php");
	require_once(__DIR__ . "/php/trade.php");
	require_once(__DIR__ . "/php/userdata_get_set.php");

	$trade_id = $_GET["trade_id"];
	$user_id = $_GET["user_id"];
	$is_trade = false;
	$comment_section_id = 0;
	if(!empty($trade_id)) {
		$is_trade = true;
		$comment_section_id = getTradeCommentSection($trade_id);
		echo("Trade Id: $trade_id<br>Comment Section Id: $comment_section_id");
	} else if (!empty($user_id)) {
		$comment_section_id = getCommentSection($user_id);
		echo("User Id: $user_id<br>Comment Section Id: $comment_section_id");
	} else {
		exit();
	}

	if(isset($_POST["delete"])) {
		echo("Delete Comment: ");
		var_dump(delete_comment($comment_section_id));
	}

	if(isset($_POST["submit"]) && !isset($_POST["delete"])) {
		echo("Create Comment: ");
		var_dump(create_comment($comment_section_id, $_POST["rating"], $_POST["reason"]));
	}

	if(isset($_POST["set"])) {
		if($is_trade) {
			setTradeCommentSectionStatus($trade_id, $_POST["enabled"]);
		} else {
			setUserCommentSectionStatus($_POST["enabled"]);
		}
	}

	echo("<pre>");
	var_dump(list_comments($comment_section_id));
	var_dump(get_rating($comment_section_id));
	echo("</pre>");

	$rating = "";
	$reason = "";
	$comment = get_comment($comment_section_id);
	if(!empty($comment)) {
		$rating = $comment["rating"];
		$reason = $comment["reason"];
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<?php if(logedin()) { ?>
		<form action="" method="post">
			<input type="number" name="rating" value="<?php echo($rating) ?>"><br>
			<textarea name="reason" rows="8" cols="80"><?php echo($reason) ?></textarea><br>
			<input type="submit" name="submit" value="Submit">
			<input type="submit" name="delete" value="Delete Comment">
		</form>
		<?php
			}
			if(($is_trade && owner_of_trade($trade_id)) || (!$is_trade && $user_id === logedin())) {
				echo("<form action=\"\" method=\"post\">");
				if($is_trade) {
					echo("<input type=\"checkbox\" name=\"enabled\" value=\"1\" " . (getTradeCommentSectionStatus($trade_id) ? "checked" : "") . ">");
				} else {
					echo("<input type=\"checkbox\" name=\"enabled\" value=\"1\" " . (getUserCommentSectionStatus($user_id) ? "checked" : "") . ">");
				}
				echo("<input type=\"submit\" name=\"set\" value=\"Set\">");
				echo("</form>");
			}
		?>
	</body>
</html>
