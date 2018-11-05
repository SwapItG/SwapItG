<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");

  if ($_POST["submit"] == "create") {
    $item = array("name" => "","count" => "","attributes" => array());
    $item_list = array($item);
    $item_demand = array($item);
    for ($i=0; $i < count($_POST["item_name"]);$i++) {
      $item["name"] = $_POST["item_name"][$i];
      $item["count"] = $_POST["item_count"][$i];
      for ($j=0;$j<count($_POST["item_attribute"][$i]);$j++) {
        $item["attributes"][$j] = $_POST["item_attribute"][$i][$j];
      }
      $item_list[$i] = $item;
    }
    $item_demand = $item_list;
    $result = create_trade($_POST["description"],$_POST["game"],$item_list,$item_demand);
    echo "<a style='color:yellow'>".$result."</a>";
    unset($_POST);
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="assets/css/global_var.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Filter.css">
    <link rel="stylesheet" href="assets/css/searchBarForGames.css">
    <title>swapitG create trade</title>
    <style>
      .item {
        margin:5px;
        padding:5px;
      }
      #item_container {
        background-color:#AAA;
        width:300px;
        margin-top:15px;
        margin-bottom:15px;
      }
      #item_demand {
        background-color:#AAF;
        width:300px;
        margin-top:15px;
        margin-bottom:15px;
      }
      #body {
        background-color: var(--light-black);
        color:white;
      }
      .searchbar,.searchInput {
        width:300px;
        height:50px;
      }
      .addAttributeButton, .removeAttributeButton {
        width:30px;
        height:30px;
        margin-right:5px;
      }
      .addAttributeButton {
        float:right;
      }
      .removeAttributeButton {
        float:right;
      }
    </style>
  </head>
  <body id="body">
<!--<?php //include "source/header.php" ?>-->
    <form method="POST" action="">
      <!-- searchbar for games -->
      <div class="input-group searchbar">
        <input type="text" name="game" value="<?PHP echo $_POST["game"] ?>" autocomplete="off" onclick="loadAutoCompleteScript()" onfocus="focusAutoComplete(this)" onfocusout="defocusAutoComplete(this)"  oninput="updateAutocomplete(this.value)" id="searchInputBar" class="form-control searchInput" placeholder="Select your game..." />
        <input class="searchButton" type="submit" value="search" />
        <img id="loadScriptIMG" src="assets/img/loading.svg" alt="" />
        <div id="autocompleteContainer">
          <ul id="autocompleteList">
          </ul>
        </div>
      </div>
      <!-- item adder/remover -->
      <div>
        <div id="item_container"></div>
        <button type="button" onclick="addNewItem()">+</button><br><br>
      </div>
      <!--<div>
        <div id="item_demand"></div>
        <button type="button" onclick="addNewItemDemand()">+</button><br><br>
      </div>-->
      <input type="text" placeholder="description" name="description" /><br>
      <input type="submit" name="submit" value="create" />
    </form>
    <div id="JSelementContainer" style="display:none">
      <div id="item_attribute_container">
        <input type="text" placeholder="attribute" name="item_attribute[attrx][]" />
        <button type="button" class="addAttributeButton" onclick="deleteAttribute(this)">-</button>
      </div>
    </div>
    <!-- offer -->
    <script>
      var item_container = document.getElementById("item_container");
      var item = document.getElementById("item");
      var item_html = `<div class="item" id="item">
        <h6>Item <span id="itemNum"></span></h6>
        <input type="text" placeholder="item name" name="item_name[]" /><br>
        <input type="text" placeholder="item count" name="item_count[]" /><br>
        <div id="attributes">
        </div>
        <button type="button" class="addAttributeButton" onclick="addAttribute(this)">+</button>
        <br>
        <button type="button" onclick="clearItem(this)">remove item</button>
      </div>`;
      var item_list = new Array();
      var storage = document.getElementById("storage");

      function addNewItem() {
        var item_id = new Array();
        for (i=0;i<20;i++) {
          var item = document.getElementById("item" + i);
          if (item != null) {
            item_id[i] = item.id;
          }
        }
        //item_string = item_string.replace("attrx", item_list.length);
        item_container.innerHTML += item_html;
        item = document.getElementById("item");
        item.id = "item" + item_id.length;
        item = document.getElementById("item" + item_id.length);
        item.childNodes[1].id = "item_title" + item_id.length;
        document.getElementById('item_title' + item_id.length).childNodes[1].innerHTML = item_id.length;
        item.innerHTML = item.innerHTML.replace('attrx', item_id.length);
        item.innerHTML = item.innerHTML.replace('attributes', 'attributes' + item_id.length);
      }
      function clearItem(object) {
        object.parentElement.remove();
        var item_list = new Array();
        var item_id = new Array();
        for (i=0;i<20;i++) {
          var item = document.getElementById("item" + i);
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
              var oldID = item_list[i].id.replace('item', '');
              item_list[i].id = "item" + i;
              var newID = item_list[i].id.replace('item', '');
              item_list[i].childNodes[1].id = "item_title" + i;
              document.getElementById('item_title' + i).innerHTML = "Item " + i;
              document.getElementById('attributes' + oldID).id = "attributes" + newID;
              for(j=0;j<document.getElementById('attributes' + newID).childNodes.length;j++) {
                  document.getElementById('attributes' + newID).innerHTML = document.getElementById('attributes' + newID).innerHTML.replace('item_attribute['+oldID+'][]','item_attribute['+newID+'][]');
              }
        }
      }
      function addAttribute(object) {
        var id = object.parentElement.id.replace('item', '');
        var attribute = document.getElementById('item_attribute_container');
        var attribute_container = document.getElementById('JSelementContainer').innerHTML;
        attribute.id = "";
        attribute.innerHTML = attribute.innerHTML.replace('attrx', id);
        document.getElementById('attributes' + id).appendChild(attribute);
        document.getElementById('JSelementContainer').innerHTML = attribute_container;
      }
      function deleteAttribute(object) {
        object.parentElement.remove();
      }
    </script>
    <!-- demand -->
    <!-- searchbar -->
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
    <?PHP print_r($item_list)?></pre><br><br>
    <span style='color:lightgreen'>Nachfrage:</span><pre style="color:white">
    <?PHP print_r($item_demand)?></pre>
  </body>
</html>
