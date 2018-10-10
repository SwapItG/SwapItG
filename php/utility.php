<?php
	function mail_simplify($s) {
		return strtolower(trim($s));
	}

	function password_security_check($s) {
		if(empty($s) || is_array($s)) {
			return false;
		}
		if(strlen($s) < 8) {
			return false;
		}
		if(strlen($s) > 32) {
			return false;
		}
		return true;
	}

	function valid_name_check($s) {
		if(empty($s) || is_array($s)) {
			return false;
		}
		if(strlen($s) < 3) {
			return false;
		}
		if(strlen($s) > 32) {
			return false;
		}
		return true;
	}
?>
