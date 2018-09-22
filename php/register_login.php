<?php
	require_once(__DIR__ . "/db_config.php");
	require_once(__DIR__ . "/utility.php");
	require_once(__DIR__ . "/session.php");

	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> passwords dont match
	//return 3 -> password has to be at least 8 chars long and at most 32 chars long
	//return 4 -> email already used
	//return 5 -> email couldnt be sent
	function register($name, $email, $password, $password2) {
		global $db_host, $db_name, $db_user, $db_pass;
		$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$pdo->query("DELETE FROM user WHERE verification_code IS NOT NULL AND TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, creation_time)) > 600");

		if(empty($name) || is_array($name) || empty($email) || is_array($email) || empty($password) || is_array($password) || empty($password2) || is_array($password2)) {
			return 1;
		}

		if($password !== $password2) {
			return 2;
		}

		if(password_security_check($password)) {
			return 3;
		}

		$sql = "SELECT email FROM user WHERE email = :email";
        $sth = $pdo->prepare($sql);
		$sth->bindParam(":email", string_simplify($email), PDO::PARAM_STR);
        $sth->execute();

		if($sth->rowCount() != 0) {
			return 4;
		}

		$verification_code = hash("sha256", microtime() . (string)rand() . $email);
		$password_hash = password_hash($password, PASSWORD_BCRYPT);

		$reg_link = "https://swapitg.com/firstlogin/$verification_code";
		$subject = "Registration";
		$mailfile = fopen(__DIR__ . "/mail_template.html", "r") or die("Unable to open file!");
		$message = strtr(fread($mailfile, filesize(__DIR__ . "/mail_template.html")), array('$reg_link' => $reg_link, '$name' => htmlspecialchars($name)));
		fclose($mailfile);
		$sender_email = "no-reply@swapitg.com";
		$sender_name = "SwapitG no-reply";

		if(mail($email, $subject, wordwrap($message, 70, "\r\n"), "From: $sender_name<$sender_email>\r\nContent-type: text/html; charset=utf-8", " -f " . $sender_email)) {
			$sql = "INSERT INTO user (email, name, password, verification_code) VALUES (:email, :name, :password_hash, :verification_code)";
	        $sth = $pdo->prepare($sql);
			$sth->bindParam(":email", string_simplify($email), PDO::PARAM_STR);
			$sth->bindParam(":name", $name, PDO::PARAM_STR);
			$sth->bindParam(":password_hash", $password_hash, PDO::PARAM_STR);
			$sth->bindParam(":verification_code", $verification_code, PDO::PARAM_STR);
	        $sth->execute();

			return 0;
		} else {
			return 5;
		}
	}

	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> verification-code time passed or wrong email
	//return 3 -> wrong password
	function firstlogin($email, $password, $verification_code) {
		global $db_host, $db_name, $db_user, $db_pass;
		$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		$pdo->query("DELETE FROM user WHERE verification_code IS NOT NULL AND TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, creation_time)) > 600");

		if(empty($email) || is_array($email) || empty($password) || is_array($password) || empty($verification_code) || is_array($verification_code)) {
			return 1;
		}

		$sql = "SELECT id, password FROM user WHERE verification_code = :verification_code AND email = :email";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":verification_code", $verification_code, PDO::PARAM_STR);
		$sth->bindParam(":email", string_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 2;
		}

		$id_password = $sth->fetch();
		if(!password_verify($password, $id_password["password"])) {
			return 3;
		}

		$sql = "UPDATE user SET verification_code = NULL WHERE id = :id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $id_password["id"], PDO::PARAM_INT);
		$sth->execute();

		login($email, $password);
		header("Location: https://swapitg.com");
		return 0;
	}

	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> account does not exist
	//return 3 -> wrong password
	function login($email, $password) {
		global $db_host, $db_name, $db_user, $db_pass;
		$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		if(empty($email) || is_array($email) || empty($password) || is_array($password)) {
			return 1;
		}

		$sql = "SELECT id, password FROM user WHERE email = :email";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":email", string_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 2;
		}

		$id_password = $sth->fetch();
		if(!password_verify($password, $id_password["password"])) {
			return 3;
		}

		session_login($id_password["id"]);
		return 0;
	}

	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty
	//return 3 -> wrong email
	//return 4 -> wrong password
	function delete_account($email, $password) {
		global $db_host, $db_name, $db_user, $db_pass;
		$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

		if(!logedin()) {
			return 1;
		}

		if(empty($email) || is_array($email) || empty($password) || is_array($password)) {
			return 2;
		}

		$sql = "SELECT id, password FROM user WHERE id = :id AND email = :email";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $_SESSION["user_id"], PDO::PARAM_INT);
		$sth->bindParam(":email", string_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 3;
		}

		$id_password = $sth->fetch();
		if(!password_verify($password, $id_password["password"])) {
			return 4;
		}

		$sql = "DELETE FROM user WHERE id = :id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $id_password["id"], PDO::PARAM_INT);
		$sth->execute();
		return 0;
	}
?>
