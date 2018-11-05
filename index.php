<?PHP
  include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");

  $gameID = getGameID($_GET["game"]);
  $tradeCounts = 4;
  $item_row_count = 3;
  if(empty($_GET["game"])) {
  	$_GET["game"] = null;
  }
  if (isset($_POST["pagelink"])) {
      $tradeList = list_trades($tradeCounts,$_POST["pagelink"],$gameID,0,0,0,0);
  } else {
      $tradeList = list_trades($tradeCounts,0,$gameID,0,0,0,0);
  }
  if (empty($tradeList['list'])) {
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

  //echo "<a style='color:yellow'>".$tradeList["backward"]."#</a>";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwapitG</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
</head>
  <body id="body">

    <!-- Placeholder -->
    <div style="height:5vh">
    </div>
    <!-- Filter Area -->
    <div>
      <table style="width:100%">
        <tr>
        <!-- Simple Filter -->
        <td style="width:300px">
          <form method="GET" action="">
            <div class="input-group searchbar">
              <input type="text" name="game" value="<? echo $_GET["game"] ?>" autocomplete="off" onclick="loadAutoCompleteScript()" onfocus="focusAutoComplete(this)" onfocusout="defocusAutoComplete(this)"  oninput="updateAutocomplete(this.value)" id="searchInputBar" class="form-control searchInput" type="text" placeholder="Select your game..." />
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
          <form method="GET" action="">
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
          <?PHP
          //echo '<pre style="color:white">'; echo print_r(getTradeData($tradeList["list"][0])["item_offer"][0]); echo'</pre>';
            for ($i=0;$i<count($tradeList["list"]);$i++) {
              $tradeID = $tradeList["list"][$i];
              $trade = getTradeData($tradeID);
              echo '
              <tr>
                <td class="userPost" colspan="6">
                  <div class="userPostDIV">
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
            <?PHP
              echo '<li class="paginationLi"><a class="paginationNumb">'.$_POST["currentPageCount"].'</a></li>';
            ?>
            <li class="page-item"><button type="submit" name="pagelink" <?PHP if($tradeList["forward"] === false) {echo "disabled=''";echo " class='disabledPaginationButton'";}else{echo " class='enabledPaginationButton'";} ?> value="<?PHP echo($tradeList["forward"]) ?>"><i id="right-arrow" class="paginationIcon"></i></button></li>
          </ul>
        </form>
      </nav>
    </div>
  <?PHP//include ($_SERVER['DOCUMENT_ROOT'] . "pages/source/footer.php")?>
  <script type="text/javascript">
  var gameList = new Array(<?PHP
  $api_url = 'https://swapitg.com/getGames';
  $json = json_decode(file_get_contents($api_url), true);
  for ($i=0;$i < count($json);$i++) {
    echo "'".$json[$i]["name"]."',";
  }?>);
  var gameListPic = new Array(<?PHP
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
