<?php
	//input: string $s (e-mail address)
	function mail_simplify($s) {
		//set string to lowercase
		$simplified = strtolower(trim($s));
		$result = "";
		$quotation = false;
		$quotation_text = "";
		$bracket = 0;
		$backslash = false;
		//loop through every caracter
		for ($i=0; $i < strlen($simplified); $i++) {
			//if caracter is qoutation
			if($simplified[$i] == "\"" && !$backslash) {
				//invert quotation variable
				$quotation = !$quotation;

				//if quotation marks ended
				if(!$quotation) {
					//if text inside quotations only contains certain
					if(preg_match("/\A[A-Za-z0-9.!#$%&'*\+\-\/=?^_`{|}~]+\z/", $quotation_text)) {
						//add quotation_text inside quotations to output
						$result .= $quotation_text;
					} else {
						//add quotation_text including quotations to output
						$result .= "\"$quotation_text\"";
					}
				}
			}
			//if bracket opens and it isnt after an backslash or inside quotations increment the bracket variable
			if($simplified[$i] == "(" && !$backslash && !$quotation) {
				$bracket++;
			}
			//if character isnt inside brackets and is not an quotation mark wich is not after an backslash
			if($bracket == 0 && !($simplified[$i] == "\"" && !$backslash)) {
				//if not inside quotation marks
				if(!$quotation) {
					//add character to output
					$result .= $simplified[$i];
				} else {
					//add chracter to quotation_text
					$quotation_text .= $simplified[$i];
				}
			}
			//if bracket closes and it isnt after an backslash or inside quotations decrement the bracket variable
			if($simplified[$i] == ")" && !$backslash && !$quotation) {
				if($bracket > 0) {
					$bracket--;
				}
			}
			//if character is backslash and previous character isnt an backslash set backslash to true else set backslash to false
			if($simplified[$i] == "\\" && !$backslash) {
				$backslash = true;
			} else {
				$backslash = false;
			}
		}
		return $result;
	}

	//input: string $s (password)
	function password_security_check($s) {
		//empty or array check
		if(empty($s) || is_array($s)) {
			return false;
		}
		//length checks
		if(strlen($s) < 8) {
			return false;
		}
		if(strlen($s) > 32) {
			return false;
		}
		return true;
	}

	//input: string $s (user name)
	function valid_name_check($s) {
		//empty or array check
		if(empty($s) || is_array($s)) {
			return false;
		}
		//length checks
		if(strlen($s) < 3) {
			return false;
		}
		if(strlen($s) > 32) {
			return false;
		}
		return true;
	}
?>
