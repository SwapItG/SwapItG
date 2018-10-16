<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/register_login.php");
if (empty(logedin())) {
    header('Location: https://swapitg.com');
}

include ($_SERVER['DOCUMENT_ROOT'] . "pages/source/header.php");
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwapG Account</title>
    <style>
        :root {
        --pic-size:90px;
        --frame-size:100px;
        }
        div,p,ul {
          border:none;
        }
        body {
          background-color:#151515;
          color:#DDD;
          overflow-x:hidden;
        }
        #accountInformation {

        }
        #boxDisplay {
            display: flex;
            flex-flow: row wrap;
            margin:auto;
            width: 1000px;
            background-color:#333338;
            border-radius:3px;
        }
        #accountDisplay {
            width:700px;
            display: flex;
            flex-flow: row wrap;
            padding-left:15px;
        }
        #accountPicBox {
          background-color:inherit;
          width:20%;
        }
        #accountNameBox {
          background-color:inherit;
          height:40px;
          width:80%;
          font-size: 25px;
          font-family:Arial, Helvetica, sans-serifs;
        }
        #accountInfoBox {
          background-color: inherit;
          width:85%;
          margin-top:15px;
        }
        #settingsDisplay {

            width:20%;
            height:200px;
            float:right;
        }
        #accountPic {
          object-fit: cover;
          width:var(--pic-size);
          height:var(--pic-size);
          margin-left:calc((var(--frame-size) - var(--pic-size))/2);
          margin-top:calc((var(--frame-size) - var(--pic-size))/2);
          border-radius:6px;
        }
        #picFrame {
          width:var(--frame-size);
          height:var(--frame-size);
          background-color:#555;
          border-bottom:solid;
          border-right:solid;
          border-color:#444;
          border-radius:2px;
          box-shadow:0px 0px 5px #111;
        }
        #accountSettingsButton {
          width:150px;
          height:35px;
          margin-top:45px;
          margin-left:15px;
        }
        #linkedAccountsBox {
          margin-top:50px;
          list-style-type: none;
          width:100%;
        }
        #accountTopPlaceholder {
          width:100%;
          margin-top:25px;
        }
        .account {
          padding-top:5px;
          font-size:14px;
        }
        .accountPic {
          width:20px;
          margin-right:15px;
          margin-left:-25px;
        }
        @media only screen and (max-width: 875px) {
            #linkedAccountsBox {
              margin-top:5px;
            }
            #boxDisplay {
                width:600px;
            }
            #accountDisplay {
                width:100%;
            }
            #accountPicBox {
                width:20%;
            }
            #accountInfoBox {
                padding-right:50px;
                width:100%;
            }
            #settingsDisplay {
                width:100%;
                height:100px;
                font-size:15px;
                font-display:block;
            }
            #accountSettingsButton {
              margin-top:0px;
            }
        }
        @media only screen and (max-width: 550px) {
            .account {
              height:15px;
              word-break:break-all;
            }
            #linkedAccountsBox {
              padding-top:25px;
            }
            #boxDisplay {
                width:360px;
            }
            #accountDisplay {
                width:100%;
            }
            #accountPicBox {
                width:35%;
            }
            #accountNameBox {
                width:65%;
            }
        }
    </style>
  </head>
  <body>
      <div id="accountInformation">
          <div id="boxDisplay">
              <div id="accountDisplay">
                  <div id="accountTopPlaceholder"></div>
                  <div id="accountPicBox"><div id="picFrame"><img id="accountPic" src="<?PHP echo getImage() ?>" /></div></div>
                  <div id="accountNameBox"><?PHP echo getName(); ?></div>
                  <div id="accountInfoBox"><span><?PHP echo getInfo(); ?></span></div>
                  <ul id="linkedAccountsBox">
                      <li class="account"><img class="accountPic" src="/assets/img/mailicon.png" /><?PHP echo '<span>'.getEmail().'</span>' ?></li>
                      <li class="account"><img class="accountPic" src="/assets/img/steamico.png" /><?PHP echo '<span>'.getSteamProfile().'</span>' ?></li>
                  </ul>
              </div>
              <div id="settingsDisplay">
                  <form method="POST" action="editAccount"><input type="submit" id="accountSettingsButton" value="⚙ EDIT PROFILE" /></form>
              </div>
          </div>
      </div>
  </body>
</html>
