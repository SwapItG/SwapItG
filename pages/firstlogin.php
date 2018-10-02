<?php
	require_once(__DIR__ . "/../php/register_login.php");
	session_start();
	echo (validateAccount($_SESSION["EMail"],$_SESSION["Password"]));


	function validateAccount($email,$passw) {
		if (empty($email) && empty($passw) && empty($_GET["c"])) {
				header('Location:'.'https://swapitg.com');
		}
		$_SESSION["verificationCode"] = $_GET["c"];
		if (isset($_POST["manualValidation"])) {
				$email = $_POST["email"];
				$passw = $_POST["password"];
				unset($_POST["manualValidation"]);
		}
		$validation = firstlogin($email, $passw, $_GET["c"]);
		unset($_SESSION["RegConfirm"]);
		if (!empty($email) && !empty($passw)) {
				if ($validation == 0) {
						return "<p>Validation completed!</p><a href='https://swapitg.com'>Home</a>";
				} else {
						switch ($validation) {
								case 1:
									return "Some informations were lost. Please try again!<br><a href='registration.php'>Registration</a>";
									break;
								case 2:
									return "Verification-code time passed or wrong email<br><a href='registration.php'>Registration</a>";
									break;
								case 3:
									unset($_POST["password"]);
									header('Location:'.'https://swapitg.com/firstlogin.php');
									return "The password is incorrect!";
									break;
								case 4:
									return "<p>This account has already been validated!</p><a href='https://swapitg.com'>Home</a>";
									break;
						}
				}
		} else {
				echo '<form method="POST" action="">
									<input type="text" name="email" />
									<input type="text" name="password" />
									<input type="submit" name="manualValidation" value="submit" />
							</form>
				';
		}
	}
?>
<html>
	<head>
	</head>
	<body>

	</body>
</html>
