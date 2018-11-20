<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");

  if ($_POST["submit"] == "create") {
    $item = array("name" => "","count" => "","attributes" => array());
    $item_offer = array($item);
    $item_demand = array($item);
    for ($i=0; $i < count($_POST["item_name_WANT"]);$i++) {
      $item["name"] = $_POST["item_name_WANT"][$i];
      if ($_POST["item_count_WANT"][$i] == "") {
        $item["count"] = 1;
      } else {
        $item["count"] = $_POST["item_count_WANT"][$i];
      }
      for ($j=0;$j<count($_POST["item_attribute_WANT"][$i]);$j++) {
        $item["attributes"][$j] = $_POST["item_attribute_WANT"][$i][$j];
      }
      $item_demand[$i] = $item;
    }
    for ($i=0; $i < count($_POST["item_name_HAS"]);$i++) {
      $item["name"] = $_POST["item_name_HAS"][$i];
      if ($_POST["item_count_HAS"][$i] == "") {
        $item["count"] = 1;
      } else {
        $item["count"] = $_POST["item_count_HAS"][$i];
      }
      for ($j=0;$j<count($_POST["item_attribute_HAS"][$i]);$j++) {
        $item["attributes"][$j] = $_POST["item_attribute_HAS"][$i][$j];
      }
      $item_offer[$i] = $item;
    }
    if (count($_POST["item_name_WANT"]) == 0) {
      $item_null = array("name" => "","count" => "","attributes" => array());
      $item_null["name"] = "offer";
      $item_null["count"] = 1;
      $item_demand[0] = $item_null;
    }
    if (count($_POST["item_name_HAS"]) == 0) {
      $item_null = array("name" => "","count" => "","attributes" => array());
      $item_null["name"] = "offer";
      $item_null["count"] = 1;
      $item_offer[0] = $item_null;
    }
    $result = create_trade($_POST["description"],$_POST["game"],$item_offer,$item_demand);
    unset($_POST);
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="assets/css/global_var.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <link rel="stylesheet" href="assets/css/createTrade.css">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
    <title>swapitG create trade</title>
  </head>
  <body id="body">
  <?php include "source/header.php" ?>
  <?php include ($_SERVER['DOCUMENT_ROOT'] . "assets/css/button.html"); ?>
    <div id="CreateTradeDiv">
      <form method="POST" action="">
        <!-- searchbar for games -->
        <div id="searchbarDiv">
          <div class="input-group searchbar">
          <input type="text" name="game" value="<?PHP echo $_POST["game"] ?>" autocomplete="off" onclick="loadAutoCompleteScript()" onfocus="focusAutoComplete(this)" onfocusout="defocusAutoComplete(this)"  oninput="updateAutocomplete(this.value)" id="searchInputBar" class="form-control searchInput" placeholder="Select your game..." />
          <input class="searchButton" type="submit" value="search" />
          <img id="loadScriptIMG" src="assets/img/loading.svg" alt="" />
          <div id="autocompleteContainer">
            <ul id="autocompleteList">
            </ul>
          </div>
        </div>
        </div>
        <!-- item adder/remover -->
        <div id="item_creator_box">
          <div class="item_div" id="HAS">
            <div id="item_container"></div>
            <h3 class="item_header">OFFER</h3>
            <button id="addNewItemHAS" type="button" onclick="addNewItem('HAS')" class="submitButton saveButton addNewItemButton">add item</button>
          </div>
          <div class="item_div" id="WANT">
            <div id="item_container"></div>
            <h3 class="item_header">WANT</h3>
            <button id="addNewItemWANT" type="button" onclick="addNewItem('WANT')" class="submitButton saveButton addNewItemButton">add item</button>
          </div>
        </div>
        <textarea type="text" id="item_textarea" placeholder="description" name="description" autocomplete="off"></textarea><br>
        <input type="submit" name="submit" class="submitButton cancelButton" value="create" />
      </form>
    </div>
    <div id="JSelementContainer" style="display:none">
    </div>
    <div id="JSItemElementContainer" style="display:none">
    </div>
    <script src="assets/js/game_arrays.js"></script>
    <script>
      var item_container = document.getElementById("item_container");
      var item_list = new Array();
      var storage = document.getElementById("storage");

      function addNewItem(container) {
        var item_html = `<div class="item" id="item`+container+`">
              <h6>Item <span id="itemNum"></span></h6>
              <input id="item_name_input" class="item_input" type="text" placeholder="item name" name="item_name_`+container+`[]" autocomplete="off" /><br>
              <input id="item_count_input" class="item_input" type="text" placeholder="item count" name="item_count_`+container+`[]" autocomplete="off" /><br>
              <div id="attributes`+container+`">
              </div>
              <button class="add_attribute_item" type="button" onclick="addAttribute(this,'`+container+`')"><i class="fas fa-plus"></i></button>
              <button type="button" class="remove_item_button" onclick="clearItem(this,'`+container+`')"><i class="fas fa-times"></i></button>
            </div>`;
        item_container = document.getElementById(container);
        document.getElementById("JSItemElementContainer").innerHTML = item_html;
        var item = document.getElementById(container).childNodes[0];
        var item_id = new Array();
        var isSpecialGame = false;
        var selectedGame = "";
        for (i=0;i<20;i++) {
          var item = document.getElementById("item" + container + i);
          if (item != null) {
            item_id[i] = item.id;
          }
        }
        item = document.getElementById("item" + container);
        item.id = "item" + container + item_id.length;
        item = document.getElementById("item" + container + item_id.length);
        item.childNodes[1].id = "item_title" + container + item_id.length;
        document.getElementById('item_title' + container + item_id.length).childNodes[1].innerHTML = item_id.length;
        item.innerHTML = item.innerHTML.replace('attrx', item_id.length);
        item.innerHTML = item.innerHTML.replace('attributes', 'attributes' + item_id.length);
        item_container.appendChild(item);
        document.getElementById(container).appendChild(document.getElementById("addNewItem" + container));
        for ($i=0;$i<gameList.length;$i++) {
          if (gameList[$i].toLowerCase() == document.getElementById("searchInputBar").value.toLowerCase()) {
            isSpecialGame = true;
            selectedGame = gameList[$i].replace(/ /g,"_").toLowerCase();
          }
        }
        if (isSpecialGame) {
          addSpecialAttribute(selectedGame,container,item_id.length);
        }
      }
      function clearItem(object,container) {
        object.parentElement.remove();
        var item_list = new Array();
        var item_id = new Array();
        console.log(container);
        for (i=0;i<20;i++) {
          var item = document.getElementById("item" + container + i);
          if (item != null) {
            //storage.appendChild(item);
            item_list[i] = item;
            item_id[i] = item.id;
          }
        }
        item_id = item_list.filter(function (el) {
          return el != null;
        });
        item_list = item_list.filter(function (el) {
          return el != null;
        });
        for (i=0;i<item_list.length;i++) {
              var oldID = item_list[i].id.replace('item' + container, '');
              item_list[i].id = "item" + container + i;
              var newID = item_list[i].id.replace('item' + container, '');
              item_list[i].childNodes[1].id = "item_title" + container + i;
              document.getElementById('item_title' + container + i).innerHTML = "Item " + i;
              document.getElementById('attributes' + oldID + container).id = "attributes" + newID + container;
              for(j=0;j<document.getElementById('attributes' + newID + container).childNodes.length;j++) {
                  document.getElementById('attributes' + newID + container).innerHTML = document.getElementById('attributes' + newID + container).innerHTML.replace('item_attribute_'+container+'['+oldID+'][]','item_attribute_'+container+'['+newID+'][]');
              }
        }
      }
      function addAttribute(object,container) {
        var attribute_html = `<div id="item_attribute_container">
          <input type="text" class="item_input" id="item_attribute_input" placeholder="attribute" name="item_attribute_`+container+`[attrx][]" autocomplete="off" />
          <button type="button" class="deleteAttributeButton" onclick="deleteAttribute(this)"><i class="fas fa-minus"></i></button>
        </div>`;
        var id = object.parentElement.id.replace('item' + container, '');
        document.getElementById('JSelementContainer').innerHTML = attribute_html;
        var attribute = document.getElementById('item_attribute_container');
        attribute.id = "";
        attribute.innerHTML = attribute.innerHTML.replace('attrx', id);
        document.getElementById('attributes' + id + container).appendChild(attribute);
        document.getElementById('JSelementContainer').innerHTML = attribute_container;
      }
      function addSpecialAttribute(selectedGame,container,id) {
        game_array = new Array();
        console.log(selectedGame);
        for ($i=0;$i<games.length;$i++) {
          if (games[$i][0] == selectedGame) {
            game_array = games[$i];

          }
        }
        console.log(game_array);
        for ($i=1;$i<game_array.length;$i++) {
          var attribute_html = "";
          if (game_array[$i][0] == "array") {
            attribute_html = `<div id="item_attribute_container">
            <select class="item_input" id="item_attribute_input" name="item_attribute_`+container+`[attrx][]">`;
              for ($j=1;$j<game_array[$i].length;$j++) {
                if ($j==1){
                  attribute_html += `<option selected>`+game_array[$i][$j]+`</option>`;
                } else {
                  attribute_html += `<option>`+game_array[$i][$j]+`</option>`;
                }
              }
            attribute_html += `</select><button type="button" class="deleteAttributeButton" onclick="deleteAttribute(this)"><i class="fas fa-minus"></i></button>
            </div>`;
          } else {
            attribute_html = `<div id="item_attribute_container">
              <input type="text" class="item_input" id="item_attribute_input" placeholder="`+game_array[$i]+`" name="item_attribute_`+container+`[attrx][]" autocomplete="off" />
              <button type="button" class="deleteAttributeButton" onclick="deleteAttribute(this)"><i class="fas fa-minus"></i></button>
            </div>`;
          }
          document.getElementById('JSelementContainer').innerHTML = attribute_html;
          var attribute = document.getElementById('item_attribute_container');
          attribute.id = "";
          attribute.innerHTML = attribute.innerHTML.replace('attrx', id);
          document.getElementById('attributes' + id + container).appendChild(attribute);
          //document.getElementById('JSelementContainer').innerHTML = attribute_container;
        }
      }
      function deleteAttribute(object) {
        object.parentElement.remove();
      }
    </script>
    <script>
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
    <span style='color:lightgreen'>Angebot:</span><pre style="color:white">
    <?PHP print_r($item_offer)?></pre><br><br>
    <span style='color:lightgreen'>Nachfrage:</span><pre style="color:white">
    <?PHP print_r($item_demand);
    echo "<a style='color:yellow'>".$result."</a>";?></pre>
  </body>
</html>
