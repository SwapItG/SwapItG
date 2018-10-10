<?
include("../php/steamAPI.php");
$errorMessage;
if (isset($_POST["steamuser"]) && $_POST["subUser"] == "check") {
    if (!empty($_POST["steamuser"])) {
        if (getSteamID($_POST["steamuser"]) != false) {
            $steamid = getSteamID($_POST["steamuser"]);
        } else {
            $steamid = "";
            if (empty($steamid)) {
                $errorMessage = "No account found";
            }
        }
    }
} else {
    if (isset($_POST["steamid"]) && $_POST["subUser"] == "submit") {
      if (!empty($_POST["steamid"])) {
          $json = getSteamInfo($_POST["steamid"]);
          $steamid = $json["response"]["players"][0]["steamid"];
          if (empty($steamid)) {
              $errorMessage = "No account found";
          }
      } else {
          $steamid = "";
      }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            #steamName {
                display:inherit;
            }
            #steamID {
                display:none;
            }
            #error {
              color:red;
            }
        </style>
    </head>
    <body>
        <div>
            <div id="steamName">
                <form method="POST" action="">
                    <span>https://steamcommunity.com/id/</span><input type="text" name="steamuser" placeholder="customURL" />
                    <input type="submit" name="subUser" value="check" />
                </form>
            </div>
            <div id="steamID">
                <form method="POST" action="">
                    <span>https://steamcommunity.com/profiles/</span><input type="text" name="steamid" placeholder="steamID" />
                    <input type="submit" name="subUser" value="submit" />
                </form>
            </div>
            <a> Login with steamID/customURL</a>
            <select id="steamInputToggler" onchange="changeForm()">
                <option>NAME</option>
                <option selected="selected">ID</option>
            </select>
        </div>
        <a id="error"><? echo $errorMessage ?></a>
        <h1><? echo getSteamName($steamid)?></h1>
        <img src="<? echo getLargeImage($steamid)?>">
        <ul>
            <li>SteamID64: <?=$steamid?></li>
            <li>Display Name: <?=getSteamName($steamid);?></li>
            <li>URL: <?=getSteamURL($steamid)?></li>
            <li>Small Avatar: <? echo getSmallImage($steamid)?></li>
            <li>Medium Avatar: <? echo getImage($steamid)?></li>
            <li>Full Avatar: <? echo getLargeImage($steamid)?></li>
            <li>Real Name: <? echo getRealName($steamid)?></li>
        </ul>
    </body>
</html>
<script>
var steamForm = document.getElementById("steamInputToggler");
var steamID = document.getElementById("steamID");
var steamName = document.getElementById("steamName");

function changeForm() {
    if (steamForm.value == "ID") {
        steamID.style.display = "inherit";
        steamName.style.display = "none";
    }
    if (steamForm.value == "NAME") {
        steamID.style.display = "none";
        steamName.style.display = "inherit";
    }
}

</script>
