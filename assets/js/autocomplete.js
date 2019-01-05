var gamesearchInput = document.getElementById("searchInputBar");
var autocompleteContainer = document.getElementById("autocompleteContainer");
var selectedGameList = new Array()
var selectedGameListPic = new Array();
var selectedGameListCounter;
var loadedImages = 0;
var imageNum = 0;

updateAutocomplete("");

function updateAutocomplete(slogan,param) {
  var autoSuggestElements = document.getElementsByClassName("autoSuggest");
  var sloganlength = slogan.length;
  autocompleteList.innerHTML = "";
  autocompleteContainer.style.visibility = "visible";
  slogan = slogan.toLowerCase();
  selectedGameList = [];  // sorgt dafür, dass bei jedem Neuaufruf die Liste wieder geleert ist
  selectedGameListPic = [];
  selectedGameListID = [];
  selectedGameListCounter = -1; // verhindert dass eine zusätzliche leere Zeile entsteht
  for(i=0;i<gameList.length;i++) {
      if(slogan == (gameList[i].substr(0,sloganlength).toLowerCase()) && param != "all" && slogan != "") {
          selectedGameListCounter++;
          selectedGameList[selectedGameListCounter] = gameList[i];
          selectedGameListPic[selectedGameListCounter] = gameListPic[i];
          selectedGameListID[selectedGameListCounter] = gameListID[i];
          autocompleteContainer.style.width = "auto";
      }
  }
  if (slogan == "" || slogan == null) {
    selectedGameList = gameList;
    selectedGameListPic = gameListPic;
    selectedGameListID = gameListID;
  }
  for(i=0;i<gameList.length;i++) {
    if(selectedGameList[i] != "" && selectedGameList[i] != null) {
        var l = document.createElement("LI");
        var t = document.createTextNode(selectedGameList[i]);
        l.appendChild(t);
        l.className = "autoSuggest";
        l.id = selectedGameListID[i];
        l.onclick = function applyAuto() {
          var fillText = "";
          fillText = this.innerHTML.split("<");
          fillText = fillText[0];
          gamesearchInput.value = fillText;
          updateAutocomplete(fillText);
          updateAttributes();
          autocompleteContainer.style.visibility = "hidden";
        };
        autocompleteList.appendChild(l);
        var img = document.createElement("IMG");
        l.innerHTML = selectedGameList[i];
        l.appendChild(img);
        img.className = "autoSuggestIMG";
        img.alt = "";
        if (selectedGameListPic[i] != "") {
            img.addEventListener('load', loadFinished);
            imageNum++;
        }
        img.src = selectedGameListPic[i];
        img.style.filter = "alpha(opacity=0)";
        autocompleteContainer.style.height = (selectedGameList.length * 40) + "px";
    } else {
      return 0;
    }
  }
  updateAttributes();
}
function autoLoad() {
    timeAfter = new Date();
    loadTime = timeAfter - timeNow;
    console.log("Searchbar Loading Time: " + loadTime+"ms");
}
function loadFinished() {
   loadedImages++;
   if (loadedImages == imageNum) {
     loadScriptIMG.style.visibility = "hidden";
     imageNum = 0;
     autoLoad();
   }
}
function defocusAutoComplete(object) {
    autocompleteContainer.style.visibility = "hidden";
}
function focusAutoComplete(object) {
        updateAutocomplete("");
        autocompleteContainer.style.visibility = "visible";
        document.getElementById("autocompleteList").style.width = "300px";
        //createAttributes(gamesearchInput.value);
}
