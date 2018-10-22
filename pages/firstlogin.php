<?php
	  require_once(__DIR__ . "/../php/register_login.php");
    require_once(__DIR__ . "/../php/userdata_get_set.php");

		$validation = firstlogin($_POST["email"], $_POST["password"], $_GET["c"]);

		if ($validation == 0) {
				echo "<p>Validation completed!</p>";
		} else {
        if (firstlogin(getEmail(),"p","p") == 4) {
            $validation = 4;
        } else {
            echo "<p>Something went wrong with your validation! Please type in your login informations again!</p>";
            echo '<form method="POST" action="">
                      <input type="text" name="email" placeholder="email" />
                      <input type="text" name="password" placeholder="password" />
                      <input type="submit" name="manualValidation" value="submit" />
                  </form>';
        }
		}
		switch ($validation) {
	        case 1:
	            $errorMessage = "All fields have to completed!";
	            break;
	        case 2:
	            $errorMessage = "verification-code time passed or wrong email";
	            break;
	        case 3:
	            $errorMessage = "password is incorrect!";
	            break;
	        case 4:
	            $errorMessage = "This account has already been validated!";
	            break;
	  }
		echo "<a style='color:red'/>".$errorMessage."</a><br />";
?>
<html>
	<head>
	</head>
	<body><br>
		<a href="https://swapitg.com">Back to Home</a>
	</body>
</html>
