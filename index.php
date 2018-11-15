<?PHP // GET SERVER TRADING DATA //
  if ($_GET["removeFilter"] == "submit") {
    unset($_GET);
    header('Location: https://swapitg.com/');
  }
  include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");

  $gameID = getGameID($_GET["game"]);
  $searchedItem = $_GET["itemSearch"];
  $itemDesire = $_GET["itemDesire"];
  $item_offer = 0;
  $item_demand = 0;
  if (isset($_POST["pagelink"])) {
    $pageLink = $_POST["pagelink"];
  } else {
    $pageLink = 0;
  }
  $tradeCounts = 5;
  $item_row_count = 3;
  if(empty($_GET["game"])) {
  	$_GET["game"] = null;
  }
  if(empty($searchedItem)) {
    $searchedItem = 0;
  }
  if ($itemDesire == "WANT") {
    $item_offer = $searchedItem;
  }
  if ($itemDesire == "HAS") {
    $item_demand = $searchedItem;
  }
  if ($itemDesire == "BOTH") {
    $item_offer = $searchedItem;
    $item_demand = $searchedItem;
  }

  $tradeList = list_trades($tradeCounts,$pageLink,$gameID,$item_offer,$item_demand,0,0);

  /*if (empty($tradeList['list'])) {
      $tradeList["list"][0] = "";
      $tradeList["list"][1] = "";
      $tradeList["list"][2] = "";
      $tradeList["list"][3] = "";
  }*/
  function getGameID($gameName) {
      $api_url = 'https://swapitg.com/getGames';
      $json = json_decode(file_get_contents($api_url), true);
      for ($i=0;$i<count($json);$i++) {
        if (strtolower($json[$i]["name"]) == strtolower($gameName)) {
            return $json[$i]["id"];
        }
      }
  }?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwapitG</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <link rel="stylesheet" href="assets/css/searchItemBar.css">
</head>
  <body id="body">
    <div id="websiteContent">
      <!-- Placeholder -->
      <div style="height:5vh">
      </div>
      <!-- Filter Area -->
      <div>
        <table style="width:100%">
          <tr>
          <!-- Simple Searchbar -->
          <td style="width:300px">
            <form method="GET">
              <div class="input-group searchbar">
                <input type="text" name="game" value="<? echo $_GET["game"] ?>" autocomplete="off" onclick="loadAutoCompleteScript()" onfocus="focusAutoComplete(this)" onfocusout="defocusAutoComplete(this)"  oninput="updateAutocomplete(this.value)" id="searchInputBar" class="form-control searchInput" type="text" placeholder="Select your game..." />
                <input class="searchButton" type="submit" value="search" />
                <img id="loadScriptIMG" class="lazyload" data-src="assets/img/loading.svg" />
                <div id="autocompleteContainer">
                  <ul id="autocompleteList">
                  </ul>
                </div>
              </div>
          </td>
          <script type="text/javascript">
            var gameList = new Array(<?PHP
            $api_url = 'https://swapitg.com/getGames';
            $json = json_decode(file_get_contents($api_url), true);
            $gameListID = array();
            for ($i=0;$i < count($json);$i++) {
              echo "'".$json[$i]["name"]."',";
              $gameListID[$i] = getGameID($json[$i]["name"]);
            }?>);
            var gameListPic = new Array(<?PHP
            $api_url = 'https://swapitg.com/getGames';
            $json = json_decode(file_get_contents($api_url), true);
            for ($i=0;$i < count($json);$i++) {
               echo "'".$json[$i]["icon_path"]."',";
            }?>);
            var gameListID = new Array(<?PHP
            for ($i=0;$i < count($gameListID);$i++) {
              echo $gameListID[$i].",";
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
          <?
            if (!empty($gameID)) {
              echo '
              <td>
                <form method="GET" action="https://swapitg.com/">
                  <img onclick="removeFilterTrigger()" class="removeFilterX lazyload" data-src="https://vignette.wikia.nocookie.net/f1wikia/images/7/7e/Red_x.png/revision/latest?cb=20120910155654">
                    <input id="removeFilterButton" style="display:none" type="submit" name="removeFilter" value="submit" />
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
        <div  id="collapse-1">
          <div class="filter">
              <div>
                <span id="itemSearchError"></span>
                <input name="itemSearch" id="itemSearchInputField" onfocus="displayItemSearchBar()" autocomplete="off" onfocusout="hideItemSearchBar()" onclick="printItemList(document.getElementById('searchInputBar').value)" placeholder="search an item for this game" value="<?PHP echo $_GET["itemSearch"]; ?>" />
                <img id="searchItemLoadingIMG" src="assets/img/loading.gif" alt="" />
                <div id="ItemAutoCompleteContainer">
                  <ul id="ItemAutoCompleteList">
                  </ul>
                </div>
              </div>
              <div id="searchAttributes">
              <select id="itemDesire" name="itemDesire">
                <option <?PHP if($_GET["itemDesire"] == "HAS"){echo "selected";} ?>>HAS</option>
                <option <?PHP if($_GET["itemDesire"] == "WANT"){echo "selected";} ?>>WANT</option>
              </select>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- User Requests -->
      <div style="overflow-x:auto">
        <table class="contentUserPostTableBox">
          <tbody><?PHP // print user trades //
              for ($i=0;$i<count($tradeList["list"]);$i++) {
                $tradeID = $tradeList["list"][$i];
                $trade = getTradeData($tradeID);
                ########## Output trades ##########
                echo '<tr>
                <td class="userPost" colspan="6">
                <div class="userPostDIV">';
                output_trade_html($trade,$item_row_count,$tradeID);
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
              <li class="page-item"><button type="submit" name="pagelink" <?PHP if($tradeList["backward"] === false) {echo "disabled=''";echo " class='disabledPaginationButton'";}else{echo " class='enabledPaginationButton'";} ?> value="0"><i id="double-arrow" class="paginationIcon"></i></button></li>
              <li class="page-item"><button type="submit" name="pagelink" <?PHP if($tradeList["backward"] === false) {echo "disabled=''";echo " class='disabledPaginationButton'";}else{echo " class='enabledPaginationButton'";} ?> value="<?PHP echo($tradeList["backward"]) ?>"><i id="left-arrow" class="paginationIcon"></i></button></li>
              <?PHP echo '<li class="paginationLi"><a class="paginationNumb">'.$_POST["currentPageCount"].'</a></li>';?>
              <li class="page-item"><button type="submit" name="pagelink" <?PHP if($tradeList["forward"] === false) {echo "disabled=''";echo " class='disabledPaginationButton'";}else{echo " class='enabledPaginationButton'";} ?> value="<?PHP echo($tradeList["forward"]) ?>"><i id="right-arrow" class="paginationIcon"></i></button></li>
            </ul>
          </form>
        </nav>
      </div>
      <!-- Javascript -->
      <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-beta.2/lazyload.js"></script>
      <script>lazyload()</script>
    </div>
  </body>
</html>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/extendedFilter.js"></script>
