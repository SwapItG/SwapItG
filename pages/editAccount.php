<?
require_once(__DIR__ . "/../php/userdata_get_set.php");
require_once(__DIR__ . "/../php/session.php");

  if (empty(logedin())) {
      unset($_POST);
      header('Location: https://swapitg.com/');
  }

  if ($_POST["submitChanges"] == "cancel") {
      unset($_POST["submitChanges"]);
      header('Location: https://swapitg.com/account');
  }

  if ($_POST["submitChanges"] == "save") {
      unset($_POST["submitChanges"]);
      setAll($_POST["name"],$_POST["steamlink"],$_POST["info"]);
      unset($_POST);
  }
?>
<?php include "../header.php" ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwapG Edit-Account</title>
    <style>
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
          margin-bottom:25px;
      }
      #accountPicIframe {
          height:140px;
          border:none;
      }
      #accountInfoArea {
          width:var(--default-width);
          height:100px;
          font-size:13px;
      }
      .inputField {
          width:var(--default-width);
          font-size:13px;
          font-family:arial;
      }
      .submitButton {
          width:100px;
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
      }
      #changepassword:hover {
          color:#99F;
          text-decoration: underline;
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
      <div id="background"></div>
      <form method="POST" action="">
        <div id="accountDisplay">
          <div id="accountInfoBox">
            <div id="accountEditBox">
                <div class="accountTopic">Name:</div>
                <div class="accountEdit" id="accountNameBox">
                    <input class="inputField" name="name" type="text" value="<? echo getName() ?>" placeholder="username" />
                </div>
                <div class="accountTopic">Email:</div>
                <div class="accountEdit" id="accountMail">
                    <span id="mail"><? echo getEMail(); ?></span>
                </div>
                <div class="accountTopic">Password:</div>
                <div class="accountEdit" id="accountPassw">
                    <span id="password"><? echo "********" ?></span>
                    <a id="changepassword">change</a>
                </div>
                <div class="accountTopic">Picture:</div>
                <iframe id="accountPicIframe" class="accountEdit" src="../editAccountPicture.php" scrolling="no"></iframe>
                <div class="accountTopic">Description:</div>
                <div class="accountEdit" id="accountInfo">
                    <textarea placeholder="your personal impressum" id="accountInfoArea" name="info"><? echo getInfo() ?></textarea>
                </div>
                <div class="accountTopic">Links:</div>
                <div class="accountEdit" id="accountLinks">
                    <input type="text" class="inputField" name="steamlink" placeholder="http://steamcommunity.com/id/" value="<? echo getSteamProfile() ?>" />
                </div>
                <div class="accountTopic"></div>
                <div class="accountEdit" id="submitBox">
                    <input class="submitButton" name="submitChanges" type="submit" value="cancel" />
                    <input class="submitButton" name="submitChanges" type="submit" value="save" />
                </div>
            </div>
          </div>
      </div>
    </form>
    </body>
</htlm>
