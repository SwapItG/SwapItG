<?php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/utility.php");
	require_once(__DIR__ . "/session.php");
	require_once(__DIR__ . "/userdata_get_set.php");
	require_once(__DIR__ . "/trade.php");
	require_once(__DIR__ . "/comment_section.php");

	//input: string $name
	//input: string $email
	//input: string $password
	//input: string $password2 (password confirmation)
	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> passwords dont match
	//return 3 -> the password, user name or email does not meet our requirements
	//return 4 -> email already used
	//return 5 -> email couldnt be sent
	function register($name, $email, $password, $password2) {
		global $pdo;

		//delete old registration attempts
		$pdo->query("DELETE FROM user WHERE verified = 0 AND TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, code_generation_time)) > 600");

		//empty or array check
		if(empty($name) || is_array($name) || empty($email) || is_array($email) || empty($password) || is_array($password) || empty($password2) || is_array($password2)) {
			return 1;
		}

		//check if passwords match
		if($password !== $password2) {
			return 2;
		}

		//check if the password, user name and email meets our requirements
		if(!password_security_check($password) || !valid_name_check($name) || strlen($email) > 256) {
			return 3;
		}

		//check if email is already used
		$sql = "SELECT email FROM user WHERE REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\")";
        $sth = $pdo->prepare($sql);
		$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
        $sth->execute();

		if($sth->rowCount() != 0) {
			return 4;
		}

		//generate verification code
		$verification_code = hash("sha256", microtime() . (string)rand() . $email);
		//hash password
		$password_hash = password_hash($password, PASSWORD_BCRYPT);

		//get data required for email
		$reg_link = "https://swapitg.com/firstlogin/$verification_code";
		$subject = "Registration";
		$mailfile = fopen(__DIR__ . "/register_mail_template.html", "r") or die("Unable to open file!");
		$message = strtr(fread($mailfile, filesize(__DIR__ . "/register_mail_template.html")), array('$reg_link' => $reg_link, '$name' => htmlspecialchars($name)));
		fclose($mailfile);
		$sender_email = "no-reply@swapitg.com";
		$sender_name = "SwapitG no-reply";

		//send email
		if(mail($email, $subject, wordwrap($message, 70, "\r\n"), "From: $sender_name<$sender_email>\r\nContent-type: text/html; charset=utf-8", " -f " . $sender_email)) {
			//if mail send sucessfully create new user wich is not verified yet
			$sql = "INSERT INTO user (email, name, password, verification_code) VALUES (:email, :name, :password_hash, :verification_code)";
	        $sth = $pdo->prepare($sql);
			$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
			$sth->bindValue(":name", htmlspecialchars($name), PDO::PARAM_STR);
			$sth->bindParam(":password_hash", $password_hash, PDO::PARAM_STR);
			$sth->bindParam(":verification_code", $verification_code, PDO::PARAM_STR);
	        $sth->execute();

			//set email and password in the session for easier verification if the user verifies the email in the same session
			start_session();
			$_SESSION["reg_email"] = $email;
			//xor password for security reasons
			$_SESSION["reg_password"] = $password ^ substr(str_pad($verification_code, strlen($password), $verification_code), 0, strlen($password));
			return 0;
		} else {
			return 5;
		}
	}

	//input: string $email
	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> verification-code time passed or wrong email
	//return 3 -> already verified
	//return 4 -> email couldnt be sent
	function register_resend_email($email) {
		global $pdo;

		//delete old registration attempts
		$pdo->query("DELETE FROM user WHERE verified = 0 AND TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, code_generation_time)) > 600");

		//empty or array check
		if(empty($email) || is_array($email)) {
			return 1;
		}

		//check if email exists and is not verified
		$sql = "SELECT name, verification_code FROM user WHERE REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\") AND verified = 0";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			//check if email exists but is already verified
			$sql = "SELECT id FROM user WHERE REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\") AND verified = 1";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
			$sth2->execute();

			if($sth2->rowCount() == 0) {
				return 2;
			} else {
				return 3;
			}
		}

		//get data required for email
		$name_verification_code = $sth->fetch();
		$name = $name_verification_code["name"];
		$verification_code = $name_verification_code["verification_code"];

		$reg_link = "https://swapitg.com/firstlogin/$verification_code";
		$subject = "Registration";
		$mailfile = fopen(__DIR__ . "/register_mail_template.html", "r") or die("Unable to open file!");
		$message = strtr(fread($mailfile, filesize(__DIR__ . "/register_mail_template.html")), array('$reg_link' => $reg_link, '$name' => htmlspecialchars($name)));
		fclose($mailfile);
		$sender_email = "no-reply@swapitg.com";
		$sender_name = "SwapitG no-reply";

		//send email
		if(mail($email, $subject, wordwrap($message, 70, "\r\n"), "From: $sender_name<$sender_email>\r\nContent-type: text/html; charset=utf-8", " -f " . $sender_email)) {
			return 0;
		} else {
			return 4;
		}
	}

	//input: string $email
	//input: string $password
	//input: string $verification_code (emailed code)
	//input: bool $session_check (if the function should look in the session variable for the login information)
	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> verification-code time passed or wrong email
	//return 3 -> wrong password
	//return 4 -> already validated
	function firstlogin($email, $password, $verification_code, $session_check = true) {
		global $pdo;

		//delete old registration attempts
		$pdo->query("DELETE FROM user WHERE verified = 0 AND TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, code_generation_time)) > 600");

		//empty or array check for the verification code
		if(empty($verification_code) || is_array($verification_code)) {
			return 1;
		}

		//check if session with email and password exists
		start_session();
		if($session_check && !empty($_SESSION["reg_email"]) && !empty($_SESSION["reg_password"])) {
			$email = $_SESSION["reg_email"];
			//xor password to get the plain text
			$password = substr($_SESSION["reg_password"] ^ str_pad($verification_code, strlen($_SESSION["reg_password"]), $verification_code), 0, strlen($_SESSION["reg_password"]));
			//try to verify with the email and password stored in the session
			if(firstlogin($email, $password, $verification_code, false) == 0) {
				//remove email and password from the session
				unset($_SESSION["reg_email"]);
				unset($_SESSION["reg_password"]);
				return 0;
			}
			//remove email and password from the session
			unset($_SESSION["reg_email"]);
			unset($_SESSION["reg_password"]);
		}

		//empty or array check for password and email
		if(empty($email) || is_array($email) || empty($password) || is_array($password)) {
			return 1;
		}

		//check if there is an unverified user with the given verification code and email
		$sql = "SELECT id, password FROM user WHERE verification_code = :verification_code AND REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\") AND verified = 0";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":verification_code", $verification_code, PDO::PARAM_STR);
		$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			//if no user was found check if the user with the email is already verified
			$sql = "SELECT id FROM user WHERE REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\") AND verified = 1";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
			$sth2->execute();

			if($sth2->rowCount() == 0) {
				return 2;
			} else {
				return 4;
			}
		}

		//get the user id and hashed password
		$id_password = $sth->fetch();
		//verify if the password is correct
		if(!password_verify($password, $id_password["password"])) {
			return 3;
		}

		//verify user
		$sql = "UPDATE user SET verification_code = NULL, code_generation_time = NULL, verified = 1, comment_section_fk = :comment_section_id WHERE id = :id";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(":comment_section_id", create_comment_section(), PDO::PARAM_INT);
		$sth->bindParam(":id", $id_password["id"], PDO::PARAM_INT);
		$sth->execute();

		//login user
		login($email, $password);
		return 0;
	}

	//input: string $email
	//input: string $password
	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> account does not exist
	//return 3 -> wrong password
	function login($email, $password) {
		global $pdo;

		//empty or array check
		if(empty($email) || is_array($email) || empty($password) || is_array($password)) {
			return 1;
		}

		//check if user exists and is verified
		$sql = "SELECT id, password FROM user WHERE REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\") AND verified = 1";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 2;
		}

		//get the user id and hashed password
		$id_password = $sth->fetch();
		//verify if the password is correct
		if(!password_verify($password, $id_password["password"])) {
			return 3;
		}

		//login user
		session_login($id_password["id"]);
		return 0;
	}

	//input: string $email
	//input: string $password
	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty
	//return 3 -> wrong email
	//return 4 -> wrong password
	function delete_account($email, $password) {
		global $pdo;

		//login check
		if(!logedin()) {
			return 1;
		}

		//empty or array check
		if(empty($email) || is_array($email) || empty($password) || is_array($password)) {
			return 2;
		}

		//check if user exists
		$sql = "SELECT id, password FROM user WHERE id = :id AND REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\")";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $_SESSION["user_id"], PDO::PARAM_INT);
		$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 3;
		}

		//get the user id and hashed password
		$id_password = $sth->fetch();
		//verify if the password is correct
		if(!password_verify($password, $id_password["password"])) {
			return 4;
		}

		//delete all trades the user created
		$trades = getTrades();
		for ($i=0; $i < count($trades); $i++) {
			delete_trade($trades[$i]);
		}

		//delete all comments the user created
		$comments = getComments();
		for ($i=0; $i < count($comments); $i++) {
			delete_comment($comments[$i]["comment_section_id"]);
		}

		//delete the comment section of the user
		delete_comment_section(getCommentSection());

		//delete user
		$sql = "DELETE FROM user WHERE id = :id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":id", $id_password["id"], PDO::PARAM_INT);
		$sth->execute();
		//logout user
		session_logout();
		return 0;
	}

	//input: string $email
	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> account does not exist
	//return 3 -> email couldnt be sent
	function password_change($email) {
		global $pdo;

		//empty or array check
		if(empty($email) || is_array($email)) {
			return 1;
		}

		//check if user exists
		$sql = "SELECT id FROM user WHERE REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\") AND verified = 1";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 2;
		}

		//get the id of the user
		$id = $sth->fetch()["id"];

		//generate verification code
		$verification_code = hash("sha256", microtime() . (string)rand() . $email);

		//get data required for email
		$change_link = "https://swapitg.com/changepassword/$verification_code";
		$subject = "Change Passsword";
		$mailfile = fopen(__DIR__ . "/change_password_mail_template.html", "r") or die("Unable to open file!");
		$message = strtr(fread($mailfile, filesize(__DIR__ . "/change_password_mail_template.html")), array('$change_link' => $change_link));
		fclose($mailfile);
		$sender_email = "no-reply@swapitg.com";
		$sender_name = "SwapitG no-reply";

		//send email
		if(mail($email, $subject, wordwrap($message, 70, "\r\n"), "From: $sender_name<$sender_email>\r\nContent-type: text/html; charset=utf-8", " -f " . $sender_email)) {
			//set the verification code
			$sql = "UPDATE user SET verification_code = :verification_code, code_generation_time = CURRENT_TIMESTAMP WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":verification_code", $verification_code, PDO::PARAM_STR);
			$sth->bindParam(":id", $id, PDO::PARAM_INT);
			$sth->execute();

			return 0;
		} else {
			return 3;
		}
	}

	//input: string $email
	//input: string $password (new password)
	//input: string $verification_code (emailed code)
	//return 0 -> worked
	//return 1 -> some parameters are empty
	//return 2 -> verification-code time passed or wrong email
	//return 3 -> the password does not meet our requirements
	function password_change_login($email, $password, $verification_code) {
		global $pdo;

		//empty or array check
		if(empty($email) || is_array($email) || empty($password) || is_array($password) || empty($verification_code) || is_array($verification_code)) {
			return 1;
		}

		//check if user exists
		$sql = "SELECT id FROM user WHERE verification_code = :verification_code AND REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\") AND verified = 1 AND TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, code_generation_time)) <= 600";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":verification_code", $verification_code, PDO::PARAM_STR);
		$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 2;
		}

		//check if password meet our requirements
		if(!password_security_check($password)) {
			return 3;
		}

		//hash password
		$password_hash = password_hash($password, PASSWORD_BCRYPT);
		//get id
		$id = $sth->fetch()["id"];

		//change password
		$sql = "UPDATE user SET verification_code = NULL, code_generation_time = NULL, password = :password_hash WHERE id = :id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":password_hash", $password_hash, PDO::PARAM_STR);
		$sth->bindParam(":id", $id, PDO::PARAM_INT);
		$sth->execute();
		return 0;
	}

	//input: string $email
	//returns true if user exsits even if he isnt verified yet else false
	function is_registered($email) {
		global $pdo;

		//delete old registration attempts
		$pdo->query("DELETE FROM user WHERE verified = 0 AND TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, code_generation_time)) > 600");

		//empty or array check
		if(empty($email) || is_array($email)) {
			return null;
		}

		//check if user exists
		$sql = "SELECT id FROM user WHERE REPLACE(email, \".\", \"\") = REPLACE(:email, \".\", \"\")";
		$sth = $pdo->prepare($sql);
		$sth->bindValue(":email", mail_simplify($email), PDO::PARAM_STR);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return false;
		} else {
			return true;
		}
	}
?>
