<?php
  require_once(__DIR__ . "/../php/register_login.php");
  session_start();
  $regValidationResult = $_GET["result"];
  $email = $_SESSION["reg_email"];
  echo '<div style="font-size:20px">';
  if($regValidationResult == 0 && is_registered($email) == true) {
      echo "Your Account has been registered. We sent an email to <a href=''>".$email."</a>, please verifiy your account.";
  } else {
      echo "Account registration failed!";
  }
  if ($_GET["resendMail"] == "resend") {
      $resendMailResult = register_resend_email($email);
      switch ($resendMailResult) {
  	        case 1:
  	            $errorMessage = " Email not found!";
  	            break;
  	        case 2:
  	            $errorMessage = " Verification-code time passed or wrong email";
  	            break;
  	        case 3:
  	            $errorMessage = " Already validated";
  	            break;
  	        case 4:
  	            $errorMessage = " Email couldnt be sent";
  	            break;
  	  }
      echo $errorMessage;
  }
  include ($_SERVER['DOCUMENT_ROOT'] . "assets/css/button.html");
?>
<html>
<body>
    <p>Depending on your Mail Service it can take longer until you receive your email!</p><br />
    <p> What Email ? Send me a new one! </p>
  </div>
  <form method="GET" action="" />
      <input class="submitButton neutralButton" name="resendMail" type="submit" value="resend" />
  </form>
</body>
</html>
