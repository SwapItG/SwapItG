<?php
	function string_simplify($s) {
		return strtolower(trim($s));
	}

	function password_security_check($s) {
		if(strlen($s) < 8) {
			return false;
		}
		if(strlen($s) > 32) {
			return false;
		}
		return true;
	}
?>
