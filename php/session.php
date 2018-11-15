<?php
	//checks if session is running and if it is not it starts a session
	function start_session() {
		if(session_id() == "") {
			session_start();
		}
	}

	//changes the session id and sets the Session variable user_id to the given user id
	//input: int $user_id
	function session_login($user_id) {
		start_session();
		session_regenerate_id();

		$_SESSION["user_id"] = $user_id;
	}

	//unsets the session and changes the session id
	function session_logout() {
		start_session();
		session_unset();
		session_regenerate_id();

		header("Location: https://swapitg.com");
	}

	//returns user id if user is logedin else it returns false
	function logedin() {
		start_session();

		if(isset($_SESSION["user_id"])) {
			return $_SESSION["user_id"];
		} else {
			return false;
		}
	}

	//sets the Session variable csrf_token to an random value
	function setToken() {
		start_session();
		$_SESSION["csrf_token"] = hash("sha256", microtime() . (string)rand());
	}

	//gets the Session variable csrf_token
	function getToken() {
		start_session();
		return $_SESSION["csrf_token"];
	}

	//checks if the given token is the same as the token stored in the session
	//input: string $token (token which was received by get/post)
	function validateToken($token) {
		if($token === getToken()) {
			$_SESSION["csrf_token"] = "";
			return true;
		} else {
			$_SESSION["csrf_token"] = "";
			return false;
		}
	}
?>
