<?php
	require_once(__DIR__ . "/../php/db_connect.php");

	if(isset($_GET["user_id"])) {
		//get Image
		$sql = "SELECT image FROM user WHERE id = :id";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(":id", $_GET["user_id"], PDO::PARAM_INT);
		$sth->execute();
		$image = $sth->fetch()["image"];

		//output default image if image is not set
		if(empty($image)) {
			header("Content-Type: image/jpg");
			fpassthru(fopen(__DIR__ . "/../assets/img/defaultPic.jpg", "rb"));
		} else {
			$finfo = new finfo(FILEINFO_MIME_TYPE);
			header("Content-Type: " . $finfo->buffer($image));
			echo($image);
		}
	}
?>
