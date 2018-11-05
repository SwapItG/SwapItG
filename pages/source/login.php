<?php
  require_once($_SERVER['DOCUMENT_ROOT']  . "php/register_login.php");
  require_once($_SERVER['DOCUMENT_ROOT']  . "php/userdata_get_set.php");
  require_once($_SERVER['DOCUMENT_ROOT']  . "php/session.php");
  setToken();
  login($_POST["logEMail"],$_POST["logPassw"]);
?>
<?php
  echo '<li><a class="nav-link-correction navItemAlign" href="https://swapitg.com/">HOME</a></li>';
  if (empty(logedin())) {
    echo '<li><a class="nav-link-correction navItemAlign" href="registration">Register</a></li>';
    echo '<div id="loginContainer">
              <form method="POST" action="">
                  <input class="logInput" type="text" name="logEMail" placeholder="email" />
                  <input class="logInput" type="password" name="logPassw" placeholder="password" />
                  <input id="logSubmit" type="submit" name="submitLog" value="submit" />
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
                          <li>
                              <form method="POST" action="https://swapitg.com/logout">
                                  <input type="hidden" name="csrf_token" value="'.getToken().'" />
                                  <input id="signOutButton" type="submit" name="logout" value="SIGN OUT" />
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
        <style type="text/css">
            :root {
              --header-height:75px;
              --form-height:20px;
              --form-input-width:115px;
              --form-submit-width:125px;
              --form-submit-height:30px;
            }
              .logInput {
                height:var(--form-height);
                width:var(--form-input-width);
                font-size:12px;
                border:none;
                border-bottom:solid;
                border-bottom-color:#999;
                background-color:rgba(0,0,0,0);
                border-width:1px;
                color:white;
                transition:0.1s;
                padding-left:5px;
                transition:0.1s;
              }
              .logInput:hover {
                  border-color:var(--skyblue);
                  border-bottom-color:var(--lightweight-orange);
              }
              .navItemAlign {
                  height:100%;
                  display:block;
                  display: flex;
                  align-items: center;
                  text-transform: uppercase;
                  font-weight:bold;
                  color:white !important;
              }
              .navSubLink {
                  color:white;
                  text-decoration:none;
              }
              .navSubLink:hover {
                  color:orange;
              }
              #loginContainer {
                  position:absolute;
                  top:calc(var(--header-height) - (var(--form-height)/2) * 1.2);
                  padding-right:45px;
                  width:100%;
                  left:0px;
                  text-align:right;
              }
              #logSubmit {
                  height:calc(var(--form-height) + 4px);
                  width:var(--form-submit-width);
                  font-size:12px;
                  width:75px;
                  border:solid;
                  border-color:rgba(255,255,255,1);
                  background-color:rgba(255,255,255,0);
                  color:white;
                  font-family:sans-serif;
                  transition:0.1s;
                  border-radius:4px;
                  border-width:1px;
              }
              #logSubmit:hover {
                cursor:pointer;
                border-color:rgb(185,185,185);
                border-width:2px;
                border-top-color:rgba(0,0,0,0);
                border-left-color:rgba(0,0,0,0);
                background-color:white;
                color:black;
              }
              #logSubmit:active {
                background-color:var(--strong-orange);
                color:white;
                border-color:rgba(0,0,0,0);
                font-weight:bold;
                border-width:1px;
              }
              #profileCollapseMenu {
                  box-shadow:0px 4px 1px rgba(0,0,0,0.5);
                  text-transform: none;
                  font-weight:normal;
                  position:absolute;
                  margin-right:75px;
                  width:225px;
                  border:none;
                  border-color:red;
                  right:0px;
                  top:var(--header-height);
                  background-color:var(--light-black);
                  padding-bottom:15px;
                  padding-top:15px;
                  border-radius:6px;
              }
              #profilePic {
                  margin-bottom:5px;
                  margin-left:15px;
                  object-fit: cover;
                  height:40px;
                  width:45px;
                  border-radius:6px;
              }
              #signOutButton {
                  height:var(--form-submit-height);
              }
              #uname:hover,#collapseArrow:hover,#navHome:hover {
                  color:orange;
                  cursor:pointer;
              }
              .collapseMenuLinks:hover {
                  color:orange !important;
              }
              .nav-link-correction {
                color:var(--lightweight-orange) !important;
              }
              .nav-link-correction:hover {
                  text-decoration: none !important;
                  color:white !important;
              }
              @media only screen and (max-width: 767px) {
                  #profileCollapseMenu {
                      left:0px;
                      top:calc(var(--header-height) * 2.4);
                      border-bottom-right-radius:8px;
                      z-index:3;
                  }
                  #tempName {
                      margin-left:17px;
                  }
                  #loginContainer {
                      top:calc(var(--header-height) * 1.75);
                      padding-left:10px;
                  }
                  profileCollapseMenu li {
                      margin-bottom:15px;
                  }
                  .logInput {
                      width:100px;
                      height:20px;
                  }
              }
        </style>
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
