<?php include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php") ?>
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
        }
        .contentUserPostHeaderTable {
            border-collapse:collapse;
            border-width:1px;
            width:100%;
            min-width: 900px;
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
            width:16.6%;
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
            margin-top:25px;
            margin-bottom:-25px;
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
          position: relative;
          width:35px;
          display:none;
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
              <form method="POST" action="">
                <div class="input-group searchbar">
                  <input autocomplete="off" onclick="loadAutoCompleteScript()" onfocus="focusAutoComplete(this)" onfocusout="defocusAutoComplete(this)"  oninput="updateAutocomplete(this.value)" id="searchInputBar" class="form-control searchInput" type="text" placeholder="Select your game..." />
                  <input class="searchButton" type="submit" value="search" />
                  <img id="loadScriptIMG" src="assets/img/loading.svg" />
                  <div id="autocompleteContainer">
                      <ul id="autocompleteList">

                      </ul>
                  </div>
                </div>
              </form>
          </td>
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
            for ($i=0;$i<8;$i++) {
              echo '<tr>
                  <td style="border:none" colspan="6">
                      <div>
                          <table class="contentUserPostTable">
                              <tr>
                                  <td style="">
                                      <div class="contentUserPostNameDIV">
                                          FloX'.($i+12).'aaaaa
                                      </div>
                                  </td>
                                  <td style="background-image:none;border:none" colspan="5">
                              </tr>
                              <tr>
                                  <td>Rocket League
                                  <td style="font-size:13px">I would like to trade some wheels
                                  <td>'.$i.'m ago
                                  <td>Steam
                                  <td>Item
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
    <div class="paginationDiv" style="height:10vh">
      <nav style="margin-left:calc(50% - 133px)">
          <ul class="pagination" style="position:inherit">
              <li class="page-item"><a class="page-link" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
              <li class="page-item"><a class="page-link">1</a></li>
              <li class="page-item"><a class="page-link">2</a></li>
              <li class="page-item"><a class="page-link">3</a></li>
              <li class="page-item"><a class="page-link">4</a></li>
              <li class="page-item"><a class="page-link">5</a></li>
              <li class="page-item"><a class="page-link" aria-label="Next"><span aria-hidden="true">»</span></a></li>
          </ul>
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
</script>
</body>
</html>
