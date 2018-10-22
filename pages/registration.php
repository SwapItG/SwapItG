<?php
  $errorMessage;
	require_once($_SERVER['DOCUMENT_ROOT'] . "php/register_login.php");

  if ($_POST["submitRegistration"] == "CONFIRM") {
    unset($_POST["submitRegistration"]);
    $registrationResult = register($_POST["regUsername"], $_POST["regEMail"], $_POST["regPassw"], $_POST["confirmRegPassw"]);
    if ($registrationResult == 0) {
        header('Location: https://swapitg.com/registerValidation?result='.$registrationResult);
    }
  }

  switch ($regValidationResult) {
        case 1:
            $errorMessage = "All fields must be completed";
            break;
        case 2:
            $errorMessage = "passwords do not match";
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
  }
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapit</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/global_var.css">
    <style>
        body {
          background-color:var(--middle-black) !important;
          color:white !important;
        }
        h1 {
          color:var(--lightweight-orange) !important;
        }
        .regInput {
          background-color:rgba(0,0,0,0);
          border:none;
          color:white;
        }
    </style>
  </head>
  <body>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . "pages/source/header.php") ?>
    <div>
      <h1>Account Registration</h1>
      <form method="POST" action="">
        <input data-lpignore="true" autocomplete="off" class="regInput" type="text" name="regUsername" placeholder="username" value="<?php if(!empty($_POST["regUsername"])){echo $_POST["regUsername"];}?>" /> <br>
        <input data-lpignore="true" autocomplete="off" class="regInput" type="text" name="regEMail" placeholder="email" value="<?php if(!empty($_POST["regEMail"])){echo $_POST["regEMail"];}?>" /> <br>
        <input data-lpignore="true" autocomplete="off" class="regInput" type="password" name="regPassw" placeholder="password" value="<?php if(!empty($_POST["regPassw"])){echo $_POST["regPassw"];}?>">
            <abbr title="password has to be at least 8-32 chars long"><img style="height:15px" src="/assets/img/icon_help.png" /></abbr><br>
        </input>
        <input data-lpignore="true" autocomplete="off" class="regInput" type="password" name="confirmRegPassw" placeholder="repeat password" value="<?php if(!empty($_POST["regUsername"])){echo $_POST["confirmRegPassw"];}?>" /><br>
        <input data-lpignore="true" autocomplete="off" class="regSubmit" type="submit" name="submitRegistration" value="CONFIRM" />
      </form>
      <p><?php echo $errorMessage ?></p>
    </div>
  </body>
</html>
