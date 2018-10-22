<?php
	require_once(__DIR__ . "/../php/register_login.php");

session_start();
$regValidationResult = $_GET["result"];
$email = $_SESSION["reg_email"];

if($regValidationResult == 0 && is_registered($email) == true) {
    echo "Your Account has been registered. We sent an email to <a href=''>".$email."</a>, please verifiy your account.";
} else {
    echo "account registration failed!";
}

if ($_GET["resendMail"] == "resend") {
    $resendMailResult = register_resend_email($email);
    switch ($resendMailResult) {
	        case 1:
	            $errorMessage = "Email not found!";
	            break;
	        case 2:
	            $errorMessage = "verification-code time passed or wrong email";
	            break;
	        case 3:
	            $errorMessage = "already validated";
	            break;
	        case 4:
	            $errorMessage = "email couldnt be sent";
	            break;
	  }
    echo "<br />".$errorMessage;
}
?>
<html>
<body>
  <p> What Email ? Send me a new one! </p>
  <form method="GET" action="" />
      <input name="resendMail" type="submit" value="resend" />
  </form>
</body>
</html>
