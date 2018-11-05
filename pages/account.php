<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/register_login.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");

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
  $tradeList = list_trades($tradeCounts,0,$gameID,0,0,0,0);
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
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
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
          overflow-x: hidden;
        }
        #accountInformation {

        }
        .boxDisplay {
            display: flex;
            flex-flow: row wrap;
            margin:auto;
            width: 1008px;
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
          margin-top:25px;
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
        .deleteTradeIcon {
          width:20px;
          position: absolute;
          margin-top:-30px;
          margin-left:-20px;
        }
        .deleteTradeIcon:hover {
          filter: brightness(0.65);
          cursor:pointer;
        }
        .tradeDisplay {
          margin:auto;
          width: 95%;
          max-width:1040px;
          overflow-x: scroll !important;
          background-color:#333338;
          border-radius:3px;
          margin-top:25px;
          padding-left:10px;
        }
        #settingsDisplay {
        }
        @media only screen and (max-width: 875px) {
            #linkedAccountsBox {
              padding-bottom:25px;
            }
            #boxDisplay {
                width:300px;
            }
            #accountDisplay {
                width:100%;
            }
            #accountPicBox {
                width:125px;
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
                margin-bottom:-50px;
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
            #boxDisplay {
                width:360px;
            }
            #accountDisplay {
                width:100%;
            }
            #accountPicBox {
                width:125px;
            }
            #accountNameBox {
                width:65%;
            }
            .account {
              margin-top:10px;
            }
        }
    </style>
  </head>
  <body>
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
                  <li class="account"><img class="accountPic" src="/assets/img/steamico.png" /><?PHP echo '<span>'.getSteamProfile().'</span>' ?></li>
                </ul>
              </div>
              <div id="settingsDisplay">
                <form method="POST" action="editAccount"><input type="submit" id="accountSettingsButton" value="⚙ EDIT PROFILE" /></form>
              </div>
          </div>
      </div>
      <div id="userTrades">
        <div class="tradeDisplay">
        <div>
          <table class="contentUserPostHeaderTable">
            <tbody>
              <?PHP
              //echo '<pre style="color:white">'; echo print_r(getTradeData($tradeList["list"][0])["item_offer"][0]); echo'</pre>';
                for ($i=0;$i<count($tradeList["list"]);$i++) {
                  $tradeID = $tradeList["list"][$i];
                  $trade = getTradeData($tradeID);
                  if (getName($trade["user_id"]) == getName()) {
                  echo '
                  <tr>
                    <td class="userPost" colspan="6">
                      <div class="userPostDIV">
                      <form method="POST" action="https://swapitg.com/account?trade='.$tradeID.'">
                        <label>
                          <input style="display:none" type="submit" name="deleteTrade" value="delete" />
                          <img type="submit" class="deleteTradeIcon" src="assets/img/x_icon.png" />
                        </label>
                      </form>
                        <table class="contentUserPostTable">
                          <tr>
                            <td colspan="3">
                              <table class="contentUserPostHeaderTrade">
                                <tr>
                                  <td class="contentUserPostNameDIV">
                                    <img class="userIMG" src="'.getImage($trade["user_id"]).'"/>
                                    <span class="userName">'.getName($trade["user_id"]).'</span>
                                  </td>
                                  <td class="contentUserPostHeaderTradeGame">'.getGameName($trade["game_id"]).'
                                    <img class="gameIcon" src="'.getGameIcon($trade["game_id"]).'" />
                                  </td>
                                  <td class="contentUserPostTime">'.$trade["creation_time"].'</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr>';
                            ########### Output for User Offer ############
                            echo "<td><table class='item_table item_table_has'><tr><th class='userPostTH' colspan='".$item_row_count."'>HAS</th></tr><tr>";
                            $row_split = 0;
                            for ($j=0;$j<count($trade["item_offer"]);$j++) {
                              $tradeData = $trade["item_offer"][$j];
                              $row_split++;
                              echo '<td>'.$tradeData["name"].'</td>';
                              if ($row_split >= $item_row_count) {
                                echo "</tr><tr>";
                                $row_split = 0;
                              }
                            }
                            $row_split = $item_row_count - $row_split;
                            if ($row_split == $item_row_count) {
                              $row_split = 0;
                            }
                            for($k=0;$k<$row_split;$row_split--) {
                              echo "<td></td>";
                            }
                            echo "</table></td>";
                            ############# Output for User Demand ################
                            echo "<td><table class='item_table item_table_wants'><tr><th colspan='".$item_row_count."'>WANT</th></tr><tr>";
                            $row_split = 0;
                            for ($j=0;$j<count($trade["item_offer"]);$j++) {
                              $tradeData = $trade["item_demand"][$j];
                              $row_split++;
                              echo '<td>'.$tradeData["name"].'</td>';
                              if ($row_split >= $item_row_count) {
                                echo "</tr><tr>";
                                $row_split = 0;
                              }
                            }
                            $row_split = $item_row_count - $row_split;
                            if ($row_split == $item_row_count) {
                              $row_split = 0;
                            }
                            for($k=0;$k<$row_split;$row_split--) {
                              echo "<td></td>";
                            }
                            echo "</table></td>";
                            echo '
                          </tr>
                          <tr>
                            <td style="font-size:13px" colspan="2"><textarea class="userPostDescriptionArea" disabled="">'.$trade["description"].'</textarea></td>
                          </tr>
                        </table>
                      </div>
                    </td>
                  </tr> ';
                  }
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      </div>
  </body>
</html>
