<?php
  include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php");
  include ($_SERVER['DOCUMENT_ROOT'] . "assets/css/button.html");

  if (isset($_POST["submitPasswordChange"])) {
    $newPasswordResult = password_change_login("",$_POST["changePasswordConfirm"],$_GET["c"]);
    $newPasswordResult = error01($newPasswordResult);
  }

  $set_new_password_success_window = '
    <form method="POST" action="https://swapitg.com/editAccount">
      <div style="background-color:#444;display:inherit" class="notificationBoxBackground" class="changeRequestDiv">
          <div class="notificationBox">
              <p style="color:#0A0" class="notificationboxQuestion">password change successful!</p>
              <input type="submit" id="confirmPasswordRequest" class="submitButton cancelButton" value="continue" />
          </div>
      </div>
    </form>';
  $send_mail_password_change_result_window = '
    <div onclick="toggleChangeRequest(this)" style="background-color:#444;display:inherit" class="notificationBoxBackground" class="changeRequestDiv">
        <div class="notificationBox"">
            <p style="color:#F44" class="notificationboxQuestion"></p>
            <button id="confirmPasswordRequest" class="submitButton saveButton"><i class="fas fa-envelope"></i> OKAY</button>
        </div>
    </div>';
  $set_new_password_submit_window = '
          <div class="notificationBox">
              <form method="POST" action="https://swapitg.com/changePasswordRequest?c='.$_GET["c"].'">
                  <p style="font-size:17px" class="notificationboxQuestion">password change request</p>
                  <input data-lpignore="true" autocomplete="off" class="deletePasswortInputField" type="password" name="changePasswordConfirm" placeholder="new password" /><br>
                  <span class="error">'.$newPasswordResult.'</span>
                  <br>
                  <button type="submit" name="submitPasswordChange" id="confirmPasswordChange" class="submitButton saveButton" value="change">
                  <i class="fas fa-exchange-alt"></i> CHANGE</button>
                  <label id="exitPasswordChange" style="width:150px">
                  <img id="cancelPasswordChangeIMG" alt="" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/16/Deletion_icon.svg/240px-Deletion_icon.svg.png">
                  <input id="cancelPasswordChangeButton" type="submit" name="submitPasswordChange" value="cancel" />
                  </label>
              </form>
          </div>';
  $newPasswordResult;

  echo $set_new_password_submit_window;;

  function error01($error) { // error for setting new password
    switch ($error) {
        case 1:
          $newPasswordResult = "password must not be empty!";
          break;

        case 2:
          $newPasswordResult = "verification-code time passed";
          break;

        case 3:
          $newPasswordResult = "password doesn't meet our requirements";
          break;
    }
    return $newPasswordResult;
  }
?>
<html>
<head>
  <link rel="stylesheet" href="/assets/css/editAccount.css">
</head>
<body>
</body>
</html>
