<?php
  include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/html_output.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/steamauth.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/userdata_get_set.php");

  $selectedTrade = getTradeData($_GET["trID"]);
  $userID = $selectedTrade["user_id"];

?>
<html>
  <head>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
    <link rel="stylesheet" href="assets/css/account.css">
    <style>
      #iframeCommentSection {
        position: absolute;
        top:0;
        left: 0;
        width: 100%;
        height: 100%;
        border:none;
      }
      #iframeCommentContainer {
        overflow: hidden;
        padding-top: 50%;
        position: relative;
      }
    </style>
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-beta.2/lazyload.js"></script>
    <div id="accountInformation">
        <div class="boxDisplay" style="padding-bottom:10px">
            <div id="accountDisplay">
              <div id="accountTopPlaceholder"></div>
              <div id="accountPicBox">
                <div id="picFrame"><img id="accountPic" src="<?PHP echo getImage($userID) ?>"/></div>
              </div>
              <div id="accountNameBox"><?PHP echo getName($userID); ?></div>
              <div id="accountInfoBox"><span><?PHP echo getInfo($userID); ?></span></div>
              <ul id="linkedAccountsBox" style="margin-bottom:-5px">
                <?PHP if(!empty(get_steam_data($userID))){echo '<li class="account"><img class="accountPic" src="/assets/img/steamico.png" /><span>'.get_steam_data($userID)["profile_url"].'</span></li>';} ?>
              </ul>
            </div>
        </div>
    </div>
    <div id="userTrades">
      <div class="tradeDisplay">
        <div>
          <table class="contentUserPostHeaderTable">
            <tbody>
              <?PHP
                $tradeID = $_GET["trID"];
                $trade = $selectedTrade;
                ########## Output for trade header ##########
                echo '
                <tr>
                <td class="userPost" colspan="6">
                <div class="userPostDIV">';
                output_trade_html($trade,$item_row_count,$tradeID);
              ?>
            </tbody>
          </table>
      </div>
      <div id="iframeCommentContainer">
        <iframe id="iframeCommentSection" src="https://swapitg.com/commentSection?trID=<?PHP echo $_GET["trID"] ?>" scrolling="no"></iframe>
      </div>
    </div>
  </div>
    <script>
    lazyload();
    </script>
  </body>
</html>
