<?PHP // GET SERVER TRADING DATA //
  if ($_GET["removeFilter"] == "submit") {
    unset($_GET);
    header('Location: https://swapitg.com/');
  }
  if (isset($_GET["cookie-policy"])) {
    header('Location: https://swapitg.com/impressum?target=12');
  }
  include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");
  echo "<pre style='color:white'>";

  $item_has = 0;
  $item_want = 0;
  $searchedItem = 0;
  $attributes_has = array();
  $attributes_want = array();
  $attributes = array();
  $itemDesire = "HAS";
  $tradeCounts = 5; // Define how many trade outputs per side
  $item_row_count = 3; // When the item trade rows will break
  $old_attributes = array();
  $j = -1;
  $gameID = getGameID($_GET["game"]);
  $searchedItem = $_GET["itemSearch"];
  $itemDesire = $_GET["itemDesire"];

  if (isset($_POST["pagelink"])) {
    $pageLink = $_POST["pagelink"];
  } else {
    $pageLink = 0;
  }
  /*if ($itemDesire == "BOTH") {
    $item_want = $searchedItem;
    $item_has = $searchedItem;
  } */
  for ($i=0;$i<count($_GET["item_attribute"]);$i++) {
    if ($_GET["item_attribute"][$i] == "none" || $_GET["item_attribute"][$i] == "" || $_GET["item_attribute"][$i] == "array") {
      $old_attributes[$i] = "";
    } else {
      $old_attributes[$i] = $_GET["item_attribute"][$i];
    }
  }
  for ($i=0;$i<count($_GET["item_attribute"]);$i++) {
    if ($_GET["item_attribute"][$i] == "none" || $_GET["item_attribute"][$i] == "" || $_GET["item_attribute"][$i] == "array") {
    } else {
      $j++;
      $attributes[$j] = $_GET["item_attribute"][$i];
    }
  }
  if ($itemDesire == "HAS") {
    $item_has = $searchedItem;
    $attributes_has = $attributes;
  }
  if ($itemDesire == "WANT") {
    $item_want = $searchedItem;
    $attributes_want = $attributes;
  }

  $tradeList = list_trades($tradeCounts,$pageLink,$gameID,$item_has,$item_want,$attributes_has,$attributes_want);
  function getGameID($gameName) {
      $api_url = 'https://swapitg.com/getGames';
      $json = json_decode(file_get_contents($api_url), true);
      for ($i=0;$i<count($json);$i++) {
        if (strtolower($json[$i]["name"]) == strtolower($gameName)) {
            return $json[$i]["id"];
        }
      }
  }
  echo "</pre>";
  ?>
<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>SwapitG</title>
      <link rel="stylesheet" href="assets/css/index.css">
      <link rel="stylesheet" href="assets/min/css/Filter.css">
      <link rel="stylesheet" href="assets/min/css/searchBarForGames.css">
      <link rel="stylesheet" href="assets/css/searchItemBar.css">
      <link rel="stylesheet" type="text/css" href="assets/eu-cookie-law-popup/css/jquery-eu-cookie-law-popup.css"/>
      <style>
        body {
          width:100%;
          height:100%;
        }
      </style>
  </head>
  <body id="body" onload="rebuildAttributes()" class="eupopup eupopup-bottom">
  <div id="backgroundIMG"></div>
    <div id="websiteContent">
      <!-- Placeholder -->
      <div style="height:5vh">
      </div>
      <!-- Filter Area -->
      <form method="GET">
      <div>
        <table style="width:100%">
          <tr>
          <!-- Simple Searchbar -->
          <td style="width:300px">
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
                <div id="ItemAutoCompleteBox">
                  <div id="ItemAutoCompleteContainer">
                    <ul id="ItemAutoCompleteList">
                    </ul>
                  </div>
                </div>
              </div>
              <div id="searchAttributes">
              <select id="itemDesire" name="itemDesire">
                <option value="HAS" <?PHP if($_GET["itemDesire"] == "HAS"){echo "selected";} ?>>I Want..</option>
                <option value="WANT" <?PHP if($_GET["itemDesire"] == "WANT"){echo "selected";} ?>>I Have..</option>
              </select>
              <div id="specialAttributes">
              </div>
            </div>
          </div>
        </div>
      </div>
      </form>
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

                <div class="userPostDIV">
                <div class="userPostBackground"></div>';
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
    <?PHP include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/footer.php"); ?>
  </body>
</html>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/eu-cookie-law-popup/js/jquery-eu-cookie-law-popup.js"></script>
<script src="assets/js/extendedFilter.js"></script>
<script src="assets/js/game_arrays.js"></script>
<script>
  var specialAttributes = document.getElementById("specialAttributes");

  function rebuildAttributes() {
    var $attributes = new Array(<?PHP for($i=0;$i<count($old_attributes);$i++){echo '"'.$old_attributes[$i].'",';} ?>);
    var headerItem = "<?PHP echo $_GET["itemSearch"]; ?>";
    if (headerItem != "") {
      document.getElementById("itemSearchInputField").value = headerItem;
    }
    //console.log($attributes);
    specialAttributes.innerHTML = "";
    var searchedGame = document.getElementById("searchInputBar").value.replace(/ /g,"_").toLowerCase();
    var game_array = new Array();
    for ($i=0;$i<games.length;$i++) {
      if (searchedGame == games[$i][0]) {
        game_array = games[$i];
      }
    }
    var attribute_html = "";
    for ($i=0;$i<$attributes.length;$i++) {
      if (jQuery.type(game_array[$i+1]) == "array") {
        //console.log("select");
        attribute_html += '<select class="item_input" id="item_attribute_input" name="item_attribute[]">';
        for($j=0;$j<game_array[$i+1].length;$j++) {
          if ($attributes[$i] == game_array[$i+1][$j]) {
            attribute_html += '<option selected>'+$attributes[$i]+'</option>';
          } else {
            if (game_array[$i+1][$j] != "array") {
              attribute_html += '<option>'+game_array[$i+1][$j]+'</option>';
            }
          }
        }
        attribute_html += "</select>";
      } else {
        //console.log("input");
        attribute_html += '<input type="text" value="'+$attributes[$i]+'" name="item_attribute[]" placeholder="'+game_array[$i+1]+'" autocomplete="off" />';
      }
    }
    specialAttributes.innerHTML = attribute_html;
  }
  function updateAttributes() {
    var searchWord = "<?PHP echo $_GET["game"]; ?>".replace(/ /g,"_").toLowerCase();
    var searchedGame = document.getElementById("searchInputBar").value.replace(/ /g,"_").toLowerCase();
    specialAttributes.innerHTML = "";
    var game_array = new Array();
    for ($i=0;$i<games.length;$i++) {
      if (searchedGame == games[$i][0]) {
        game_array = games[$i];
      }
    }
    var attribute_html = "";
    for ($i=1;$i<game_array.length;$i++) {
      if (game_array[$i][0] == "array") {
        attribute_html += '<select class="item_input" id="item_attribute_input" name="item_attribute[]">';
        for ($j=1;$j<game_array[$i].length;$j++) {
          if ($j==1){
            attribute_html += '<option selected>'+game_array[$i][$j]+'</option>';
          } else {
            attribute_html += '<option>'+game_array[$i][$j]+'</option>';
          }
        }
        attribute_html += "</select>";
      } else {
        attribute_html += '<input type="text" placeholder="'+game_array[$i]+'" name="item_attribute[]" />';
      }
    }
    if (searchWord == searchedGame) {
      rebuildAttributes();
    } else {
      specialAttributes.innerHTML = attribute_html;
      document.getElementById("itemSearchInputField").value = "";
    }
  }
</script>
<script>
  function displayAttributes(object,id) {
    //console.log(id);
    attrWindow = document.getElementById(id);
    attrWindow.style.visibility = "visible";
  }
  function hideAttributes(object,id) {
    //console.log("hide: " + id);
    attrWindow = document.getElementById(id);
    attrWindow.style.visibility = "hidden";
  }
</script>
