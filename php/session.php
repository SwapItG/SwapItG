<?php
	function start_session() {
		if(session_id() == "") {
			session_start();
		}
	}

	function session_login($user_id) {
		start_session();
		session_regenerate_id();

		$_SESSION["user_id"] = $user_id;
	}

	function session_logout() {
		start_session();
		session_unset();
		session_regenerate_id();

		header("Location: https://swapitg.com");
	}

	function logedin() {
		start_session();

		if(isset($_SESSION["user_id"])) {
			return $_SESSION["user_id"];
		} else {
			return false;
		}
	}

	function setToken() {
		start_session();
		$_SESSION["csrf_token"] = hash("sha256", microtime() . (string)rand());
	}

	function getToken() {
		start_session();
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
