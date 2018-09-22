<?php
	function session_login($user_id) {
		if(session_id() == "") {
			session_start();
		}
		session_regenerate_id();

		$_SESSION["user_id"] = $user_id;
	}

	function session_logout() {
		if(session_id() == "") {
			session_start();
		}
		session_unset();
		session_regenerate_id();

		header("Location: https://swapitg.com");
	}

	function logedin() {
		if(session_id() == "") {
			session_start();
		}

		if(isset($_SESSION["user_id"])) {
			return $_SESSION["user_id"];
		} else {
			return false;
		}
	}

	function setToken() {
		if(session_id() == "") {
			session_start();
		}
		$_SESSION["csrf_token"] = hash("sha256", microtime() . (string)rand());
	}

	function getToken() {
		if(session_id() == "") {
			session_start();
		}
		return $_SESSION["csrf_token"];
	}

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
