<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/userdata_get_set.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/session.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/register_login.php");

  $error01 = error01($_GET["error01"]);
  $error02;
  $error03;

  $set_new_password_success_window = '
    <form method="POST" action="https://swapitg.com/editAccount">
      <div style="display:inherit" class="notificationBoxBackground" class="changeRequestDiv">
          <div class="notificationBox">
              <p style="color:#0A0" class="notificationboxQuestion">password change successful!</p>
              <input type="submit" id="confirmPasswordRequest" class="submitButton" value="continue" />
          </div>
      </div>
    </form>';
  $send_mail_password_change_result_window = '
    <div onclick="toggleChangeRequest(this)" style="display:inherit" class="notificationBoxBackground" class="changeRequestDiv">
        <div class="notificationBox"">
            <p style="color:#F44" class="notificationboxQuestion">'.error02($error02).'</p>
            <button id="confirmPasswordRequest" class="submitButton saveButton"><i class="fas fa-envelope"></i> OKAY</button>
        </div>
    </div>';

  if (empty(logedin())) {
      unset($_POST);
      header('Location: https://swapitg.com/');
  }
  if ($_POST["submitChanges"] == "cancel") {
      unset($_POST["submitChanges"]);
      header('Location: https://swapitg.com/account');
  }
  if ($_POST["submitChanges"] == "save") {
      setAll($_POST["name"],$_POST["steamlink"],$_POST["info"]);
      unset($_POST);
      header('Location: https://swapitg.com/account');
  }
  if ($_POST["deleteAccount"] == "confirm") {
      $result = delete_account(getEmail(),$_POST["deletePasswortConfirm"]);
      if ($result == 0) {
        unset($_POST);
        header('Location: https://swapitg.com');
      } else {
        unset($_POST);
        header('Location: https://swapitg.com/editAccount?error01='.$result);
      }
  }
  if ($_POST["passwordChangeRequest"] == "change") { // sends an email password change request
      $error02 = password_change(getEmail());
  }
  if ($_POST["submitPasswordChange"] == "change" || $_GET["cpr"] == 1) { // password reset
      $error03 = password_change_login(getEmail(),$_POST["changePasswordConfirm"],$_GET["c"]);
      $set_new_password_submit_window = '
        <div style="display:inherit" class="notificationBoxBackground" class="changeRequestDiv">
            <div class="notificationBox">
                <form method="POST" action="https://swapitg.com/editAccount?c='.$_GET["c"].'">
                    <p style="font-size:17px" class="notificationboxQuestion">password change request</p>
                    <input data-lpignore="true" autocomplete="off" class="deletePasswortInputField" type="password" name="changePasswordConfirm" placeholder="new password" /><br>
                    <span class="error">'.error03($error03).'</span>
                    <br>
                    <button type="submit" name="submitPasswordChange" id="confirmPasswordChange" class="submitButton saveButton" value="change">
                    <i class="fas fa-exchange-alt"></i> CHANGE</button>
                    <label id="exitPasswordChange" style="width:150px">
                    <img id="cancelPasswordChangeIMG" alt="" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Deletion_icon.svg/240px-Deletion_icon.svg.png">
                    <input id="cancelPasswordChangeButton" type="submit" name="submitPasswordChange" value="cancel" />
                    </label>
                </form>
            </div>
        </div>';
      if ($error03 == 0) {
        unset($_POST);
    		header("Location: https://swapitg.com/editAccount?cpr=2");
      } else {
        echo $set_new_password_submit_window;
      }
  } else if ($_POST["submitPasswordChange"] == "cancel" || $_GET["cpr"] == 1) {
    unset($_POST);
    unset($_GET);
    header('Location: https://swapitg.com/editAccount');
  } else if ($_GET["cpr"] == 2) {
      echo $set_new_password_success_window;
  }

### Error Message Outputter ####################################
  function error01($error) { // error for deleting account
    switch ($error) {
        case 1:
          $error = "You must be logged in to perform that action!";
          break;

        case 2:
          $error = "Some parameters are empty!";
          break;

        case 3:
          $error = "Used Email is wrong!";
          break;

        case 4:
          $error = "password incorrect!";
          break;
    }
    return $error;
  }
  function error02($error) { // error for sending an email password change request
    switch ($error) {
        case 0:
          $error = "We sent you an email change Request.<br>Check your mails!";
          break;

        case 1:
          $error = "Your Email couldn't be found!";
          break;

        case 2:
          $error = "This account doesn't exist!";
          break;

        case 3:
          $error = "There was an error with our email server. Please try again later!";
          break;
    }
    return $error;
  }
  function error03($error) { // error for setting new password
    if ($_GET["cpr"] == 1) {
      return "";
    }
    switch ($error) {
        case 1:
          $error = "password must not be empty!";
          break;

        case 2:
          $error = "verification-code time passed";
          break;

        case 3:
          $error = "password doesn't meet our requirements";
          break;
    }
    return $error;
  }
### ENDE ########################################
?>

<?php include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php") ?>
<html>
  <head>
    <title>SwapitG <?PHP echo getName()?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preload" href="/assets/css/editAccount.css" as="style">
    <link rel="stylesheet" href="/assets/css/editAccount.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <style type="text/css">
    </style>
  </head>
  <body>
    <!-- Box for Account Deletion -->
    <div id="deletebox" class="notificationBoxBackground">
        <div class="notificationBox">
            <p class="notificationboxQuestion"> Do you want to delete your account ? </p>
            <form method="POST" action="">
                <input data-lpignore="true" autocomplete="off" class="deletePasswortInputField" type="password" name="deletePasswortConfirm" placeholder="continue with password" /><br><br>
                <button class="submitButton deleteButton" name="deleteAccount" type="submit" value="confirm"><i class="fas fa-times"></i> DELETE</button>
                <button class="submitButton cancelButton" type="button" onclick="toggleDeleteBox()"><i class="fas fa-angle-right"></i> CANCEL</button>
            </form>
        </div>
      </div>
      <?PHP
        if ($_POST["passwordChangeRequest"] == "change") { // email password change request dialog output result
            echo $send_mail_password_change_result_window;
        }?>
    <form method="POST" action="">
        <div id="accountDisplay">
          <div id="accountInfoBox">
            <div id="accountEditBox">
                <div class="accountTopic">Name:</div>
                <div class="accountEdit" id="accountNameBox">
                    <input class="inputField" name="name" type="text" value="<? echo getName() ?>" placeholder="username" data-lpignore="true" autocomplete="off" />
                </div>
                <div class="accountTopic">Email:</div>
                <div class="accountEdit" id="accountMail">
                    <span id="mail"><?PHP echo getEMail(); ?></span>
                </div>
                <div class="accountTopic">Password:</div>
                <div class="accountEdit" id="accountPassw">
                    <span id="password"><?PHP echo "********" ?></span>
                    <form method="POST" action="">
                        <input id="changepassword" type="submit" name="passwordChangeRequest" value="change" />
                    </form>
                </div>
                <div class="accountTopic">Picture:</div>
                <iframe id="accountPicIframe" class="accountEdit" src="/pages/source/editAccountPicture.php" scrolling="no"></iframe>
                <div class="accountTopic">Description:</div>
                <div class="accountEdit" id="accountInfo">
                    <textarea placeholder="your personal impressum" id="accountInfoArea" name="info"><?PHP echo getInfo() ?></textarea>
                </div>
                <div class="accountTopic">Links:</div>
                <div class="accountEdit" id="accountLinks">
                    <input type="text" class="inputField" name="steamlink" placeholder="http://steamcommunity.com/id/" value="<?PHP echo getSteamProfile() ?>" data-lpignore="true" autocomplete="off" />
                </div>
                <div class="accountTopic"></div>
                <div class="accountEdit" id="submitBox">
                    <button class="submitButton saveButton" name="submitChanges" type="submit" value="save"><i class="fas fa-check"></i> SAVE</button>
                    <button class="submitButton deleteButton" type="button" onclick="toggleDeleteBox()"><i class="fas fa-times"></i> DELETE</button>
                    <button class="submitButton cancelButton" name="submitChanges" type="submit" value="cancel">
                    <i class="fas fa-angle-right"></i> CANCEL</button>
                    <br><span class="error"><?PHP echo $error01 ?> </span>
                </div>
            </div>
          </div>
      </div>
    </form>
  </body>
</htlm>
<script type="text/javascript">
  var deletebox = document.getElementById("deletebox");
  var deleteBoxToggle = false;

  function toggleDeleteBox() {
      if (deleteBoxToggle == true) {
        deletebox.style.display = "none";
        deleteBoxToggle = false;
      } else {
        deleteBoxToggle = true;
        deletebox.style.display = "inherit";
      }

  }

  function toggleChangeRequest(object) {
      object.style.display = "none";
  }

</script>
