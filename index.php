<?php
include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");

$gameID = getGameID($_GET["game"]);
$tradeCounts = 5;
if (isset($_POST["pagelink"])) {
    $tradeList = list_trades($tradeCounts,$_POST["pagelink"],$gameID,0,0,0,0);
} else {
    $tradeList = list_trades($tradeCounts,0,$gameID,0,0,0,0);
}

if (empty($_GET["game"]) || empty($tradeList['list'])) {
    $tradeList["list"][0] = "";
    $tradeList["list"][1] = "";
    $tradeList["list"][2] = "";
    $tradeList["list"][3] = "";
}

function getGameID($gameName) {
    $api_url = 'https://swapitg.com/getGames';
    $json = json_decode(file_get_contents($api_url), true);
    for ($i=0;$i<count($json);$i++) {
      if ($json[$i]["name"] == $gameName) {
          return $json[$i]["id"];
      }
    }
}
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swapit</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
    <style>
        div td {
            border-width:1px;
            border:none;
        }
        body {
            background-color:var(--middle-black) !important;
            color:white !important;
        }
        .removeFilterX {
          width:18px;
          margin-left:10px;
          filter:grayscale(0);
          text-align:center;
          transition:0.1s;
        }
        .removeFilterX:hover {
          cursor:pointer;
          filter:grayscale(0.75);
        }
        .contentUserPostHeaderTable {
            border-collapse:collapse;
            border-width:1px;
            width:100%;
            min-width: 400px;
            border-spacing: 0;
        }
        .contentUserPostHeaderTable th {
            border-right:solid;
            border:solid;
            width:16.6%;
            text-align: center;
            border-width:1px;
            background-color:#ccc;
        }
        .contentUserPostTable {
            border-collapse: collapse;
            width:100%;
            height:100px;
            border-radius:4px;
            margin-bottom:15px
        }
        .contentUserPostTable td {
            width:33%;
            border-width:1px;
            text-align: center;
            border-spacing:0px;
            padding:5px;
            padding-bottom:25px;
        }
        .contentUserPostDIV {
            overflow-x: auto;
        }
        .contentUserPostNameDIV {
            text-align:left;
            color:orange;
            font-size:13px;
            padding-left:15px;
            margin-bottom:-1px;
            margin-left:-1px;
        }
        .paginationDiv {
            border:none;
            text-align: center;
            width:100%;
        }
        #contentHeader th {
            height:35px;
        }
        .contentHeaderSticky {
            position: fixed;
            top: 0;
            width: 100%;
        }
        .searchbar ,.searchInput {
          height:38px !important;
          border-radius:0px !important;
          box-shadow: 0px 0px 0px rgba(0,0,0,0);
        }
        .searchInput:hover {
          background-color:#CCC;
        }
        .searchbar:focus,.searchInput:focus {
          color:var(--strong-orange) !important;
        }
        .searchButton {
          height:38px !important;
          width:75px;
          border:none;
        }
        .searchButton:hover {
          cursor:pointer;
          background-color:var(--lightweight-orange);
          transition:0.1s;
        }
        #autocompleteContainer {
          display:none;
          position:absolute;
          background-color:#333;
          margin-top:40px;
          padding-left:25px;
          color:white;
          max-height:350px;
          overflow-y: scroll;
        }
        .autoSuggest {
          cursor:pointer;
          height:35px;
          padding-left:20px;
          transition:0.1s;
          padding-right:25px;
          padding-top:5px;
          white-space: nowrap;
          min-width:270px;
        }
        .autoSuggest:hover {
          color:orange;
          border-right: solid;
          background-color:rgba(0,0,0,0.1);
        }
        #autocompleteList {
          list-style-type: none;
          margin-left:-55px;
          margin-right:10px;
        }
        .autoSuggestIMG {
          width:18px;
          margin-left:5px;
        }
        #loadScriptIMG {
          position: absolute;
          width:35px;
          display:none;
          z-index:3;
          left:100%;
        }
        @media only screen and (max-width: 767px) {
            #autocompleteContainer {
                max-height:250px;
                min-width:250px;
            }
          }
    </style>
</head>
<body id="body">
    <!-- Placeholder -->
    <div style="height:15vh">
    </div>
    <!-- Filter Area -->
    <div>
      <table style="width:100%">
        <tr>
        <!-- Simple Filter -->
          <td style="width:300px">
              <form method="GET" action="">
                <div class="input-group searchbar">
                  <input name="game" value="<? echo $_GET["game"] ?>" autocomplete="off" onclick="loadAutoCompleteScript()" onfocus="focusAutoComplete(this)" onfocusout="defocusAutoComplete(this)"  oninput="updateAutocomplete(this.value)" id="searchInputBar" class="form-control searchInput" type="text" placeholder="Select your game..." />
                  <input class="searchButton" type="submit" value="search" />
                  <img id="loadScriptIMG" src="assets/img/loading.svg" />
                  <div id="autocompleteContainer">
                      <ul id="autocompleteList">

                      </ul>
                  </div>
                </div>
              </form>
          </td>
          <?
          if (!empty($gameID)) {
              echo '
              <td>
                <form method="GET" action="">
                    <img onclick="removeFilterTrigger()" class="removeFilterX" src="https://vignette.wikia.nocookie.net/f1wikia/images/7/7e/Red_x.png/revision/latest?cb=20120910155654">
                        <input id="removeFilterButton" style="display:none" type="submit" />
                    </img>
                </form>
              </td>';
          }
          ?>
          <!-- Extended Filter -->
          <td style="float:right">
              <a style="width:100px;margin-left:25px;" class="btn btn-outline-info" data-toggle="collapse" href="#collapse-1">ext. Filter</a>
          </td>
        </tr>
      </table>
      <div class="collapse" id="collapse-1">
        <div class="filter">
          <form>
            <input placeholder="e.g Blue Chair, Sofa or Post Modern"></input><br>
            <select>
              <option value="">Type</option>
            </select>
            <select>
              <option value="">Colours</option>
            </select>
            <select>
              <option value="">Size</option>
            </select>
            <select>
              <option value="">Price</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
            <select>
              <option value="">Delivery</option>
            </select>
          </form>
        </div>
      </div>
    </div>
    <!-- User Requests -->
    <!--<div id="contentHeader">
        <table class="contentUserPostHeaderTable">
            <thead>
            <tr>
                <th> Game
                <th> Message
                <th> Time
                <th> Platform
                <th> Typ
            </tr>
            </thead>
        </table>
    </div>-->
    <div style="overflow-x:auto">
        <table class="contentUserPostHeaderTable">
            <tbody>
            <?php
            for ($i=0;$i<count($tradeList["list"]);$i++) {
              echo '<tr>
                  <td style="border:none" colspan="6">
                      <div>
                          <table class="contentUserPostTable">
                              <tr>
                                  <td style="">
                                      <div class="contentUserPostNameDIV">
                                      '.getName(getTradeData($tradeList["list"][$i])["user_id"]).'
                                      </div>
                                  </td>
                                  <td style="background-image:none;border:none" colspan="5">
                              </tr>
                              <tr>
                                  <td>'.getGameName(getTradeData($tradeList["list"][$i])["game_id"]).'
                                  <img class="autoSuggestIMG" src="'.getGameIcon(getTradeData($tradeList["list"][$i])["game_id"]).'"/>
                                  <td style="font-size:13px">'.getTradeData($tradeList["list"][$i])["description"].'
                                  <td>'.getTradeData($tradeList["list"][$i])["creation_time"].'
                              </tr>
                          </table>
                      </div>
                  </td>
              </tr> ';
            }
            ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="paginationDiv">
      <nav style="width:100px;margin-left:auto;margin-right:auto">
        <form action="" method="POST">
          <ul class="pagination">
            <li class="page-item"><button type="submit" name="pagelink" <?php echo($tradeList["backward"] !== false ? "" : "disabled") ?> value="0">|«</button></li>
            <li class="page-item"><button type="submit" name="pagelink" <?php echo($tradeList["backward"] !== false ? "" : "disabled") ?> value="<?php echo($tradeList["backward"]) ?>">«</button></li>
            <?
                echo '<li class="paginationLi"><a class="paginationNumb">'.$_POST["currentPageCount"].'</a></li>';
            ?>
            <li class="page-item"><button type="submit" name="pagelink" <?php echo($tradeList["forward"] !== false ? "" : "disabled") ?> value="<?php echo($tradeList["forward"]) ?>">»</button></li>
          </ul>
        </form>
      </nav>
    </div>
    <?php include ($_SERVER['DOCUMENT_ROOT'] . "pages/source/footer.php") ?>
<script type="text/javascript">
    var gameList = new Array(<?php
    $api_url = 'https://swapitg.com/getGames';
    $json = json_decode(file_get_contents($api_url), true);
    for ($i=0;$i < count($json);$i++) {
      echo "'".$json[$i]["name"]."',";
    }?>);
    var gameListPic = new Array(<?php
    $api_url = 'https://swapitg.com/getGames';
    $json = json_decode(file_get_contents($api_url), true);
    for ($i=0;$i < count($json);$i++) {
       echo "'".$json[$i]["icon_path"]."',";
    }?>);
    var loadScriptIMG = document.getElementById("loadScriptIMG");
    var scriptLoaded = false;
    var loadTime;
    var timeNow;
    var timeAfter;

    function loadAutoCompleteScript() {
      if (scriptLoaded == false) {
        var script = document.createElement("script")
        loadScriptIMG.style.display = "inherit";
        scriptLoaded = true;
        script.type = "text/javascript";
        script.src = "assets/js/autocomplete.js";
        document.getElementById("body").appendChild(script);
        timeNow = new Date();
      }
    }

    function removeFilterTrigger() {
      document.getElementById('removeFilterButton').click();
    }
</script>
</body>
</html>
