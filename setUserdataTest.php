<?php
	require_once(__DIR__ . "/php/restricted_page.php");
	require_once(__DIR__ . "/php/userdata_get_set.php");

	//setName("test2");
	//setSteamProfile("testprofile");
	//setInfo("testinfo");

	echo(setImage("image"));
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<form enctype="multipart/form-data" action="" method="post">
			<input type="submit" name="submit" value="submit">
		</form>
	</body>
</html>
