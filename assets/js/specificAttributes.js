var supportedGames = new Array("rocket league");
var isSupported;

function createAttributes($searchedGame) {
  $searchedGame = $searchedGame.toLowerCase();
  isSupported = false;
  for (i=0;i<supportedGames.length;i++) {
    if ($searchedGame == supportedGames[i]) {
      isSupported = true;
    }
  }
  var sel = document.createElement("select");
  var opt = document.createElement("option");
  sel.appendChild(opt);
  console.log("#{#{#{}}}");
  if (isSupported) {
    var sel = document.createElement("select");
  }
}
