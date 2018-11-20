<?php
  require_once($_SERVER['DOCUMENT_ROOT']  . "php/register_login.php");
  require_once($_SERVER['DOCUMENT_ROOT']  . "php/userdata_get_set.php");
  require_once($_SERVER['DOCUMENT_ROOT']  . "php/session.php");
  include ($_SERVER['DOCUMENT_ROOT'] . "assets/css/button.html");
?>
<?php
  $errorRSPW;
  if ($_POST["submitRSPWRquest"] == "OKAY") {
    unset($_POST);
    $_POST["rspw"] = false;
  }
  if ($_POST["rspw"] == true) {
    $errorRSPW = password_change($_POST["logEMail"]);
    switch ($errorRSPW) {
        case 0:
          $errorRSPW = "We sent you an email change Request.<br>Check your mails!";
          break;

        case 1:
          $errorRSPW = "Your Email couldn't be found!";
          break;

        case 2:
          $errorRSPW = "This account doesn't exist!";
          break;

        case 3:
          $errorRSPW = "There was an error with our email server. Please try again later!";
          break;
    }
    unset($_POST);
    unset($_GET);
    echo '
    <div id="rspwContainer">
      <div id="rspwBox"><br>
        <p>'.$errorRSPW.'</p>
        <p>
          <form method="POST" action="https://swapitg.com/">
            <button type="submit" value="OKAY" name="submitRSPWRquest" class="submitButton saveButton">
            <i class="fas fa-envelope"></i> OKAY</button>
          </form>
        </p><br>
      </div>
    </div>';
  }
?>
<?PHP
  setToken();
  login($_POST["logEMail"],$_POST["logPassw"]);

  echo '<li><a class="nav-link-correction navItemAlign" href="https://swapitg.com/">HOME</a></li>';
  if (empty(logedin())) {
    echo '<li><a class="nav-link-correction navItemAlign" href="registration">Register</a></li>';
    echo '<div id="loginContainer">
              <form method="POST" action="">
                  <input class="logInput" type="text" name="logEMail" placeholder="email" />
                  <input class="logInput" type="password" name="logPassw" placeholder="password" />
                  <input id="logSubmit" type="submit" name="submitLog" value="submit" />
                  <br><button type="submit" class="passwordLost" value="true" name="rspw">password?</button>
              </form>
          </div>';
  } else {
      echo '<li id="tempName">
              <span class="nav-link-correction navItemAlign">
                  <div id="profileCollapseMenu">
                      <ul style="list-style-type: none;">
                          <li class="collapseMenuLinks"><a class="navSubLink" href="https://swapitg.com/createTrade">new trade</a></li>
                          </li>
                          <li class="collapseMenuLinks"><a class="navSubLink" href="https://swapitg.com/account">account</a></li>
                          </li>
                          <li class="collapseMenuLinks"><a class="navSubLink" href="https://swapitg.com/editAccount">settings</a></li>
                          <li>
                              <form method="POST" action="https://swapitg.com/logout">
                                  <input type="hidden" name="csrf_token" value="'.getToken().'" />
                                  <button id="signOutButton" type="submitButton" class="submitButton deleteButton" name="logout" value="SIGN OUT">SIGN OUT</button>
                              </form>
                          </li>
                      </ul>
                  </div>
                  <span onclick="toggleUserMenu()">
                      <span id="uname">'.getName().'</span>
                      <span id="collapseArrow"> ▼</span>
                  </span>';
                  if(getImage() == ""){
                    echo '<img id="profilePic" src="../../assets/img/defaultPic.jpg" />';
                  }else {
                    echo '<img id="profilePic" src="'.getImage().'" />';
                  }
                  echo '</span></li>';
  }
?>
<html>
    <head>
    <link rel="stylesheet" href="../../../assets/css/global_var.css">
    <link rel="stylesheet" href="../../../assets/css/login.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    </head>
    <body>
    </body>
</html>
<script>
    var collapseMenu = document.getElementById("profileCollapseMenu");
    var arrow = document.getElementById("collapseArrow");
    collapseMenu.style.visibility = "hidden";
    var togglestatus = false;


    function toggleUserMenu() {
        if (togglestatus == true) {
            collapseMenu.style.visibility = "hidden";
            arrow.innerHTML = " ▼";
            togglestatus = false;
        } else {
            collapseMenu.style.visibility = "visible";
            arrow.innerHTML = " ▲";
            togglestatus = true;
        }
    }
</script>
<script>
    var loginField = document.getElementById("profileCollapseMenu");
    var profileMenu = document.getElementById("profileCollapseMenu");
    var waitSecond = false;
    var toggleStatus;

    function showMenu() {
        if (toggleStatus == true) {
            profileMenu.style.display = "none";
            loginField.style.display = "none";
            toggleUserMenu();
            toggleStatus = false;
        } else {
          if (waitSecond == false) {
                waitSecond = true;
                profileMenu.style.display = "none";
                setTimeout(showMenu, 1000);
          } else {
                profileMenu.style.display = "inherit";
                loginField.style.display = "inherit";
                waitSecond = false;
                toggleStatus = true;
          }
        }
    }

</script>
