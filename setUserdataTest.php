<?php
	require_once(__DIR__ . "/php/restricted_page.php");
	require_once(__DIR__ . "/php/userdata_get_set.php");
	
	if(isset($_POST["submit"]) && isset($_POST["name"]) && isset($_POST["profile_link"]) && isset($_POST["info"]) && isset($_FILES["image"])) {
		setAll($_POST["name"], $_POST["profile_link"], $_POST["info"], "image");
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<form enctype="multipart/form-data" action="" method="post">
			<input type="text" name="name" value="<?php echo getName(); ?>">
			<input type="text" name="profile_link" value="<?php echo getSteamProfile(); ?>">
			<input type="text" name="info" value="<?php echo getInfo(); ?>">
			<label for="form_image"><img src="<?php echo(getImage()) ?>"></label>
			<input id="form_image" type="file" name="image" value="">
			<input type="submit" name="submit" value="submit">
		</form>
	</body>
</html>
