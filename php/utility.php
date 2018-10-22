<?php
	function mail_simplify($s) {
		$simplified = strtolower(trim($s));
		$result = "";
		$quotation = false;
		$quotation_text = "";
		$bracket = 0;
		$backslash = false;
		for ($i=0; $i < strlen($simplified); $i++) {
			if($simplified[$i] == "\"" && !$backslash) {
				$quotation = !$quotation;

				if(!$quotation) {
					if(preg_match("/\A[A-Za-z0-9.!#$%&'*\+\-\/=?^_`{|}~]+\z/", $quotation_text)) {
						$result .= $quotation_text;
					} else {
						$result .= "\"$quotation_text\"";
					}
				}
			}
			if($simplified[$i] == "(" && !$backslash && !$quotation) {
				$bracket++;
			}
			if($bracket == 0 && !($simplified[$i] == "\"" && !$backslash)) {
				if(!$quotation) {
					$result .= $simplified[$i];
				} else {
					$quotation_text .= $simplified[$i];
				}
			}
			if($simplified[$i] == ")" && !$backslash && !$quotation) {
				if($bracket > 0) {
					$bracket--;
				}
			}
			if($simplified[$i] == "\\" && !$backslash) {
				$backslash = true;
			} else {
				$backslash = false;
			}
		}
		return $result;
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
