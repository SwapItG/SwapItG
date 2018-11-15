<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/register_login.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/steamauth.php");

  if ($_POST["submit"] == "cancel") {
      header('Location: https://swapitg.com/account');
  }
  if (empty(logedin())) {
      header('Location: https://swapitg.com');
  }
  if ($_POST["deleteTrade"] == "delete") {
    unset($_POST);
    delete_trade($_GET["trade"]);
    header('Location: https://swapitg.com/account');
  }

  include ($_SERVER['DOCUMENT_ROOT'] . "pages/source/header.php");
  $gameID = null;
  $tradeCounts = 50;
  $item_row_count = 3;
  $tradeList = getTrades();
  function getGameID($gameName) {
      $api_url = 'https://swapitg.com/getGames';
      $json = json_decode(file_get_contents($api_url), true);
      for ($i=0;$i<count($json);$i++) {
        if ($json[$i]["name"] == $gameName) {
            return $json[$i]["id"];
        }
      }
  }

  //echo "<a style='color:yellow'>".$tradeList["backward"]."#</a>";
?>
<html>
  <head>
    <title>SwapitG <?PHP echo getName()?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/account.css">
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
    <title>SwapG Account</title>
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-beta.2/lazyload.js"></script>
    <div id="accountInformation">
        <div class="boxDisplay">
            <div id="accountDisplay">
              <div id="accountTopPlaceholder"></div>
              <div id="accountPicBox">
                <div id="picFrame"><img id="accountPic" src="<?PHP if(getImage() == ""){echo "../../assets/img/defaultPic.jpg";}else{echo getImage();} ?>"/></div>
              </div>
              <div id="accountNameBox"><?PHP echo getName(); ?></div>
              <div id="accountInfoBox"><span><?PHP echo getInfo(); ?></span></div>
              <ul id="linkedAccountsBox">
                <li class="account"><img class="accountPic" src="/assets/img/mailicon.png" /><?PHP echo '<span>'.getEmail().'</span>' ?></li>
                <li class="account"><img class="accountPic" src="/assets/img/steamico.png" /><?PHP echo '<span>'.get_steam_data()["profile_url"].'</span>' ?></li>
              </ul>
            </div>
            <div id="settingsDisplay">
              <form method="POST" action="editAccount"><button type="submit" id="accountSettingsButton" value="⚙ EDIT PROFILE" class="submitButton neutralButton">EDIT PROFILE</button></form>
            </div>
        </div>
    </div>
    <div id="userTrades">
      <div class="tradeDisplay">
      <div>
        <table class="contentUserPostHeaderTable">
          <tbody>
            <?PHP
              for ($i=0;$i<count($tradeList);$i++) {
                $tradeID = $tradeList[$i];
                $trade = getTradeData($tradeID);
                if (getName($trade["user_id"]) == getName()) {
                  ########## Output for trade header ##########
                  echo '
                  <tr>
                    <td class="userPost" colspan="6">
                      <div class="userPostDIV">
                      <form method="POST" action="https://swapitg.com/account?trade='.$tradeID.'">
                        <label>
                          <input style="display:none;background-color:rgba(0,0,0,0)" type="submit" name="deleteTrade" value="delete" />
                          <img class="deleteTradeIcon" src="assets/img/x_icon.png" />
                        </label>
                      </form>';
                  output_trade_html($trade,$item_row_count,$tradeID);
                }
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    </div>
    <script>
    lazyload();
    </script>
  </body>
</html>
