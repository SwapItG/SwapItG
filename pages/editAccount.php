<?
  require_once(__DIR__ . "/../php/userdata_get_set.php");
  require_once(__DIR__ . "/../php/session.php");
  require_once(__DIR__ . "/../php/register_login.php");

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
        header('Location: https://swapitg.com/editAccount?error='.$result);
      }
  }
  if ($_POST["passwordChangeRequest"] == "change") {
    $passwRequestResult = password_change(getEmail());
  }

  $deleteError;
  switch ($_GET["error"]) {
      case 1:
        $deleteError = "You must be logged in to perform that action!";
        break;

      case 2:
        $deleteError = "Some parameters are empty!";
        break;

      case 3:
        $deleteError = "Used Email is wrong!";
        break;

      case 4:
        $deleteError = "password incorrect!";
        break;

  }
  switch ($passwRequestResult) {
      case 0:
        $passwRequestResult = "We sent you an email change Request.<br>Check your mails!";
        break;

      case 1:
        $passwRequestResult = "Your Email couldn't be found!";
        break;

      case 2:
        $passwRequestResult = "This account doesn't exist!";
        break;

      case 3:
        $passwRequestResult = "There was an error with our email server. Please try again later!";
        break;
  }
?>
<?php include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php") ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwapG Edit-Account</title>
    <style type="text/css">
      ::-webkit-input-placeholder { /* Chrome */
        color: var(--light-orange);
        opacity: 1;
      }
      ::-moz-placeholder { /* Firefox 19+ */
        color: var(--light-orange);
        opacity: 1;
      }
      :root {
          --pic-size:50px;
          --frame-size:54px;
          --default-width:300px;
      }
      body {
        background-color:#444;
        height:100%;
        color:#DDD;
      }
      #confirmDeleteBox {
        position:absolute;
        background-color:rgba(0,0,0,0.5);
        width:100%;
        height:calc(100vh - 97px);
        top:97px;
        text-align: center;
        display:none;
      }
      #areYouSure {
        position:absolute;
        background-color:rgba(235,235,235,1);
        width:400px;
        height:200px;
        top:calc(50% - (200px / 2) - 100px);
        text-align: center;
        display:inherit;
        left:calc(50% - 400px / 2);
        padding-top:60px;
        border-radius:4px;
      }
      #areYouSureQuestion {
        color:black;
        margin-top:-25px;
      }
      #accountDisplay {
          overflow-x: hidden;
          overflow-y: hidden;
      }
      #background {
        position:absolute;
        display:inline-block;
        background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAMklEQVQYlWNgYGAwZkAFxjjEcApgSOKTIGgSVpOxKcKqmGiFRFuNUwCHGFaT8erCGuAAtV8HLQ/j6goAAAAASUVORK5CYII=);
        background-color:rgba(0,0,0,0.35);
        min-height: 100%; /* Mindesthöhe auf 100 % (bei modernen Browsern) */
        height: auto !important; /* important Behel (bei modernen Browsern */
        height: 100%;
        width:100%;
        z-index:-1;
        overflow-y: hidden;
      }
      #accountInfoBox {
          margin:auto;
          width: 800px;
          background-color:#333338;
          border-radius:3px;
          overflow-x:hidden;
          box-shadow:inset 0px 0px 5px #000;
      }
      #accountEditBox {
          margin:auto;
          width: 1000px;
          display: flex;
          flex-flow: row wrap;
          padding-top:25px;
      }
      #errorMessage {
        color:#F44;
        font-size:13px;
        font-family:sans-serif;
        padding-left:10px;
      }
      .deletePasswortInputField {
        font-family:sans-serif;
        width:250px;
        font-size:14px;
        border-radius:2px;
        background-color:rgba(50,50,50,1);
        padding-left:10px;
        height:30px;
        color:var(--lightweight-orange);
      }
      .cancelButton {
          background-color:rgba(144, 144, 144, 1);
          border:none;
      }
      .cancelButton:hover {
          background-color:rgba(120, 117, 117, 1) !important;
      }
      .confirmButton {
          background-color:var(--lightweight-orange);
      }
      .confirmButton:hover {
          background-color:var(--lightweight-orange-hover) !important;
      }
      .accountEdit {
          width:85%;
          margin-top:25px;
      }
      .accountTopic {
          width:15%;
          padding-right:20px;
          margin-top:25px;
          text-align: right;
          font-variant: small-caps;
          font-size:15px;
      }
      #submitBox {
          text-align:left;
          margin-bottom:40px;
      }
      #accountPicIframe {
          height:140px;
          border:none;
      }
      #accountInfoArea {
          width:var(--default-width);
          height:100px;
          font-size:13px;
          padding-left:5px;
      }
      .inputField {
          width:var(--default-width);
          font-size:13px;
          font-family:arial;
          padding-left:5px;
      }
      .submitButton {
          width:100px;
          height:35px;
          border:none;
          border-radius:1px;
      }
      .submitButton:hover {
        border-bottom:solid;
        border-bottom-color:#333;
        background-color:#CCC;
        height:38px;
        cursor:pointer;
      }
      #mail {
          color:grey;
          font-family:serif;
          font-size:14px;
          letter-spacing: 1px;
      }
      #password {
          color:grey;
      }
      #changepassword {
          color:#F55;
          font-family:serif;
          font-size:12px;
          letter-spacing: 1px;
          background-color:rgba(0,0,0,0);
          border:none;
      }
      #changepassword:hover {
          color:#99F;
          text-decoration: underline;
          cursor:pointer;
      }
      @media only screen and (max-width: 767px) {
          #accountInfoBox {
              width:600px;
          }
          #accountDisplay {
              width:100%;
          }
          #accountInfoBox {
              width:600px;
          }
          #settingsDisplay {
              width:100%;
              height:100px;
              font-size:15px;
              font-display:block;
          }
      }
      @media only screen and (max-width: 550px) {
          .accountEdit {
              width:100%;
              margin-left:25px;
              margin-top:5px;
          }
          .accountTopic {
              width:15%;
              text-align:left;
          }
          #accountEditBox {
            margin-left:10px;
          }
      }
    </style>
    </head>
    <body>
      <!-- Box for Account Deletion -->
      <div  id="confirmDeleteBox">
          <div id="areYouSure">
              <p id="areYouSureQuestion"> Do you want to delete your account ? </p>
              <form method="POST" action="">
                  <input data-lpignore="true" autocomplete="off" class="deletePasswortInputField" type="password" name="deletePasswortConfirm" placeholder="continue with password" /><br><br>
                  <input class="submitButton confirmButton" name="deleteAccount" type="submit" value="confirm" />
                  <button class="submitButton cancelButton" type="button" onclick="toggleDeleteBox()">cancel</button>
              </form>
          </div>
      </div>
      <?
      if ($_POST["passwordChangeRequest"] == "change") {
          echo '
          <div onclick="toggleChangeRequest(this)" style="display:inherit" id="confirmDeleteBox" class="changeRequestDiv">
              <div id="areYouSure">
                  <p style="color:#F44" id="areYouSureQuestion">'.$passwRequestResult.'</p>
                  <button id="confirmPasswordRequest" class="submitButton">OKAY</button>
              </div>
          </div>';
      }
      ?>
      <!-- <div id="background"></div> -->
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
                    <span id="mail"><? echo getEMail(); ?></span>
                </div>
                <div class="accountTopic">Password:</div>
                <div class="accountEdit" id="accountPassw">
                    <span id="password"><? echo "********" ?></span>
                    <form method="POST" action="">
                        <input id="changepassword" type="submit" name="passwordChangeRequest" value="change" />
                    </form>
                </div>
                <div class="accountTopic">Picture:</div>
                <iframe id="accountPicIframe" class="accountEdit" src="/pages/source/editAccountPicture.php" scrolling="no"></iframe>
                <div class="accountTopic">Description:</div>
                <div class="accountEdit" id="accountInfo">
                    <textarea placeholder="your personal impressum" id="accountInfoArea" name="info"><? echo getInfo() ?></textarea>
                </div>
                <div class="accountTopic">Links:</div>
                <div class="accountEdit" id="accountLinks">
                    <input type="text" class="inputField" name="steamlink" placeholder="http://steamcommunity.com/id/" value="<? echo getSteamProfile() ?>" data-lpignore="true" autocomplete="off" />
                </div>
                <div class="accountTopic"></div>
                <div class="accountEdit" id="submitBox">
                    <input class="submitButton" name="submitChanges" type="submit" value="cancel" />
                    <input class="submitButton" name="submitChanges" type="submit" value="save" />
                    <button class="submitButton" type="button" onclick="toggleDeleteBox()">delete</button>
                    <span id="errorMessage"><? echo $deleteError ?> </span>
                </div>
            </div>
          </div>
      </div>
    </form>
    </body>
</htlm>
<script>
var deleteBox = document.getElementById("confirmDeleteBox");
var deleteBoxToggle = false;

function toggleDeleteBox() {
    if (deleteBoxToggle == true) {
      console.log("aus");
      confirmDeleteBox.style.display = "none";
      deleteBoxToggle = false;
    } else {
      deleteBoxToggle = true;
      console.log("an");
      confirmDeleteBox.style.display = "inherit";
    }

}

function toggleChangeRequest(object) {
    object.style.display = "none";
}

</script>
