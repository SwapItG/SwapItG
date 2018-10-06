<?php
require_once(__DIR__ . "/php/register_login.php");
require_once(__DIR__ . "/php/userdata_get_set.php");
require_once(__DIR__ . "/php/session.php");
setToken();
login($_POST["logEMail"],$_POST["logPassw"]);
?>

<?php
echo '<li role="presentation"><a class="nav-link-correction navItemAlign" href="https://swapitg.com/">HOME</a></li>';
if (empty(logedin())) {
  echo '<li role="presentation"><a class="nav-link-correction navItemAlign" href="registration.php" uk-scroll="offset:50">Register</a></li>';
  echo '<div id="loginContainer">
            <form method="POST" action="">
                <input class="logInput" type="text" name="logEMail" placeholder="email" />
                <input class="logInput" type="text" name="logPassw" placeholder="password" />
                <input id="logSubmit" type="submit" name="submitLog" value="submit" />
            </form>
        </div>';
} else {
    echo '<li class="nav-item" role="presentation">
            <span class="nav-link-correction navItemAlign" style="border-color:red" class="" uk-scroll="offset:50">
                <div id="profileCollapseMenu">
                    <ul style="list-style-type: none;">
                        <li class="collapseMenuLinks"><a class="navSubLink" href="account.php">new trade</a></li>
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
                if(getImage() == "data:image/jpg;base64,"){
                  echo '<img id="profilePic" src="/assets/img/defaultPic.jpg" />';
                }else {
                  echo '<img id="profilePic" src="'.getImage().'" />';
                }
                echo '</span></li>';
}
?>
<html>
    <head>
        <style>
        a,li,ul{
          border:none;
        }
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
                top:calc(var(--header-height) - (var(--form-height)/2));
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
            }
            #profileCollapseMenu {
                box-shadow:0px 4px 3px rgba(0,0,0,0.5);
                text-transform: none;
                font-weight:normal;
                position:absolute;
                margin-right:100px;
                width:225px;
                border:none;
                border-color:red;
                right:0px;
                top:var(--header-height);
                background-color:#343A40;
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
            .nav-link-correction:hover {
                text-decoration: none !important;
            }
            @media only screen and (max-width: 767px) {
                #profileCollapseMenu {
                    left:0px;
                    top:195px;
                    border-bottom-right-radius:8px;
                    z-index:3;
                }
                #loginContainer {
                    top:175px;
                    padding-left:10px;
                }
                li {
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
    console.log("toggleled");

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
