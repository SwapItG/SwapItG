var itemList = new Array();
var itemSearchError = document.getElementById("itemSearchError");
var searchItemLoadingIMG = document.getElementById("searchItemLoadingIMG");
var itemAutoCompleteContainer = document.getElementById("ItemAutoCompleteContainer");
var itemAutoCompleteList = document.getElementById("ItemAutoCompleteList");
var itemSearchInputField = document.getElementById("itemSearchInputField");
searchItemLoadingIMG.src = "assets/img/loading.gif";
searchItemLoadingIMG.src = "";

function printItemList($searchInput,$gameList) {
  if ($searchInput == "" || $searchInput == null) {
    itemSearchError.innerHTML = "You must select a game first!";
  } else {
    itemSearchError.innerHTML = "";
    searchItemLoadingIMG.src = "assets/img/loading.gif";
    $game = $searchInput;
    $.getJSON( "https://swapitg.com/getItems?game_id="+$game, function( data ) {
      if (itemSearchInputField.value == "") {
        if (data == null || data == "") {
          itemSearchError.innerHTML = "There are no items for this game!";
        } else {
          itemSearchError.innerHTML = data.length + " items found!";
        }
      }
      searchItemLoadingIMG.src = "";
      itemAutoCompleteList.innerHTML = "";
      for (i=0;i<data.length;i++) {
        var li = document.createElement("LI");
        var tnode = document.createTextNode(data[i]["name"]);
        li.appendChild(tnode);
        li.className = "autoSuggest";
        li.onclick = function applyItemToSearchBar() {
          var fillText = "";
          itemAutoCompleteContainer.style.display = "inherit";
          fillText = this.innerHTML;
          itemSearchInputField.value = fillText;
          printItemList(fillText);
          itemAutoCompleteContainer.style.display = "none";
        };
        itemAutoCompleteList.appendChild(li);
      }
      console.log(data);
  });
  }
}

function displayItemSearchBar() {
  itemAutoCompleteContainer.style.display = "inherit";
  itemAutoCompleteContainer.style.visibility = "visible";
}

function hideItemSearchBar() {
  itemAutoCompleteContainer.style.visibility = "hidden";
  itemSearchError.innerHTML = "";
}
