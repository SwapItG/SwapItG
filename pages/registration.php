<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "php/register_login.php");
  if ($_POST["submitRegistration"] == "CONFIRM" && $_POST["agb"] == "aggreed") {
    unset($_POST["submitRegistration"]);
    $registrationResult = register($_POST["regUsername"], $_POST["regEMail"], $_POST["regPassw"], $_POST["confirmRegPassw"]);
    if ($registrationResult == 0) {
        header('Location: https://swapitg.com/registerValidation?result='.$registrationResult);
    }
  } else {
    $registrationResult = 6;
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
  }
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapit</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/global_var.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <style>
        form {
          width:100%;
        }
        body {
          background-color:var(--lightweight-black) !important;
          color:var(--lightweight-black) !important;
        }
        h3 {
          color:var(--lightweight-orange) !important;
        }
        div {
          border-width:1px;
        }
        .regInput {
          width:85%;
          border-radius:2px;
          padding-left:5px;
          height:30px;
        }
        #registration_container {
          width:100%;
        }
        #registration_box {
          margin:auto;
          background-color:var(--light-black);
          max-width:700px;
          padding:25px;
          border-radius:6px;
        }
        #errorM {
          color:#F55;
          font-family:sans-serif;
          font-size: 12px;
          letter-spacing: 0.75px;
        }
        .registration_flex_box {
          max-width:90%;
          display: flex;
          flex-flow: row wrap;
          margin:auto;
        }
        .RegTopic {
          width:30%;
          max-width:150px;
          margin-bottom:10px;
          font-family:'Open Sans', sans;
          font-size: 13px;
          padding-top:30px;
          text-align: right;
          padding-right:25px;
          color:var(--skyblue);
        }
        .RegContent {
          width:70%;
          margin-bottom:10px;
          padding-top:25px;
          font-size:14px;
        }
        #agbText,#agbLink {
          color:#CCC;
          margin-bottom:40px;
          font-size:12px;
        }
        #agbText:hover {
          cursor:auto !important;
        }
        #agbLink {
          color:#d15757;
          text-decoration: underline;
        }
        @media only screen and (max-width: 550px) {
          .RegTopic {
            width:100%;
            max-width:125px;
            padding:0px;
            margin:0px;
            text-align: left;
          }
          .RegContent {
            width:100%;
            margin-bottom:25px;
            padding-top:0px;
          }
        }
    </style>
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
              <div><input class="checkbox" type="checkbox" name="agb" value="aggreed" /><a id="agbText"> by confirming you aggree with our privacy policies under <a id="agbLink" href="impressum">impressum</a></a></div><br>
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
