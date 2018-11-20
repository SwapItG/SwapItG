<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/php/userdata_get_set.php");
include ($_SERVER['DOCUMENT_ROOT'] . "/pages/source/header.php");
if (getEmail() == "julianlionsperlich@gmail.com" || getEmail() == "willi.weissnegger@gmail.com") {
  $users = getAllUsers();
  echo "<h2>User Liste</h2>";
  echo "<table>";
  for ($i=0;$i<count($users);$i++) {
      echo "<tr><td>".$users[$i]["id"]."</td>";
      echo "<td>".$users[$i]["name"]."</td>";
      echo "<td><img style='width:25px' src='".$users[$i]["image"]."' />";
      echo "</tr>";
  }
  echo "</table>";

  $users = getGameName(43);
  echo "<h2>Spiele Liste</h2>";
  echo "<table>";
  for ($i=0;$i<25;$i++) {
    if(!empty(getGameName($i))) {
      echo "<tr><td>".$i."</td>";
      echo "<td>".getGameName($i)."</td>";
      echo "<td><img style='width:25px' src='".getGameIcon($i)."' />";
      echo "</tr>";
    }
  }
  echo "</table>";
} else {
  echo '
    <h4 style="color:red">You must be logged in with an admin account to access this site!</h4>
    <form method="POST" action="https://swapitg.com">
      <input type="submit" value="I understand" />
    </form>
  ';
}
?>
<html>
  <head>
  </head>
  <body>
  </body>
</html>
