<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "php/register_login.php");
  if ($_POST["submitRegistration"] == "CONFIRM" && $_POST["agb"] == "aggreed") {
    unset($_POST["submitRegistration"]);
    $registrationResult = register($_POST["regUsername"], $_POST["regEMail"], $_POST["regPassw"], $_POST["confirmRegPassw"]);
    if ($registrationResult == 0) {
        unset($_POST);
        header('Location: https://swapitg.com/registerValidation?result='.$registrationResult);
    }
  } else {
    if (!isset($_POST["agb"]) && $_POST["submitRegistration"] == "CONFIRM") {
      $registrationResult = 6;
    }
  }
  include ($_SERVER['DOCUMENT_ROOT'] . "pages/source/header.php");
  include ($_SERVER['DOCUMENT_ROOT'] . "assets/css/button.html");
  $errorMessage = "";

  switch ($registrationResult) {
        case 1:
            $errorMessage = "All fields must be completed";
            break;
        case 2:
            $errorMessage = "passwords doesn't match";
            break;
        case 3:
            $errorMessage = "password does not meet the requirements";
            break;
        case 4:
            $errorMessage = "email is already in use";
            break;
        case 5:
            $errorMessage = "email couldn't be send";
            break;
        case 6:
            $errorMessage = "You must accept our privacy policy";
            break;
        default:
          $errorMessage = "";
  }
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapit Registration</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/global_var.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <link rel="stylesheet" href="assets/css/registration.css">
  </head>
  <body>
    <div>
      <form method="POST" action="">
        <div id="registration_container">
          <h3 style="width:100%;text-align:center">Account Registration</h3>
          <div id="registration_box">
            <div class="registration_flex_box">
              <div class="RegTopic">Username</div>
              <div class="RegContent"><input data-lpignore="true" autocomplete="off" class="regInput" type="text" name="regUsername" placeholder="" value="<?php if(!empty($_POST["regUsername"])){echo $_POST["regUsername"];}?>" /></div>
              <div class="RegTopic">Email</div>
              <div class="RegContent"><input data-lpignore="true" autocomplete="off" class="regInput" type="text" name="regEMail" placeholder="" value="<?php if(!empty($_POST["regEMail"])){echo $_POST["regEMail"];}?>" /> </div>
              <div class="RegTopic">Password</div>
              <div class="RegContent">
              <input data-lpignore="true" autocomplete="off" class="regInput" type="password" name="regPassw" placeholder="" value="<?php if(!empty($_POST["regPassw"])){echo $_POST["regPassw"];}?>">
                <abbr title="password has to be at least 8-32 chars long"><img style="height:15px" src="/assets/img/icon_help.png" /></abbr>
              </input></div>
              <div class="RegTopic">Repeat Password</div>
              <div class="RegContent"><input data-lpignore="true" autocomplete="off" class="regInput" type="password" name="confirmRegPassw" placeholder="" value="<?php if(!empty($_POST["regUsername"])){echo $_POST["confirmRegPassw"];}?>" /></div>
              <div class="RegTopic"></div>
              <div class="RegContent"><input class="checkbox" type="checkbox" name="agb" value="aggreed" /><a id="agbText"> by confirming you aggree with our privacy policies under <a id="agbLink" href="impressum">impressum</a></a></div>
              <div class="RegTopic"></div>
              <div class="RegContent"><button type="submit" class="submitButton saveButton" name="submitRegistration" value="CONFIRM"><i class="fas fa-check"></i> CONFIRM</button></div>
              <div class="RegTopic"></div>
              <div class="RegContent" id="errorM"><?php echo $errorMessage ?></div>
          </div>
        </div>
      </div>
    </form>
  </body>
</html>
