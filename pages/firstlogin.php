<?php
	require_once(__DIR__ . "/../php/register_login.php");
	echo(firstlogin($_POST["logEMail"], $_POST["logPassw"], $_GET["c"]));
?>
<html>
	<head>
	</head>
	<body>
		<form method="POST" action="">
			<input type="text" name="logEMail" placeholder="email" />
			<input type="text" name="logPassw" placeholder="password" />
			<input type="submit" name="submitValidation" />
		</form>
	</body>
</html>
