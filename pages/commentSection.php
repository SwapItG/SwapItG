<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/userdata_get_set.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/comment_section.php");
  $selectedTrade = getTradeData($_GET["trID"]);
  $userID = $selectedTrade["user_id"];
  $tradeCommentSectionID = getCommentSection($userID);
  if (isset($_POST["submitComment"])) {
    create_comment($tradeCommentSectionID,$_POST["commentRating"],$_POST["writtenComment"]);
    unset($_POST);
  }
  if ($_GET["dl"] == 1) {
    delete_comment($tradeCommentSectionID);
    header('Location: https://swapitg.com/commentSection?trID='.$_GET["trID"]);
  }
  include ($_SERVER['DOCUMENT_ROOT'] . "assets/css/button.html");
?>
<html>
  <head>
    <link rel="stylesheet" href="/assets/css/global_var.css">
    <link rel="stylesheet" href="/assets/css/commentSection.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
  </head>
  <body>
    <h3 id="commentSectionTitle">GIVE FEEDBACK:</h3>
    <div style="color:white" id="commentSection">
      <?PHP if(empty(get_comment($tradeCommentSectionID)) && logedin()) { ?>
      <form method="POST">
        <div id="writeComment">
          <input type="text" name="writtenComment" autocomplete="off" class="commentInput" />
          <select name="commentRating" class="commentRatingSelect">
            <option value="1">1 ★</option>
            <option value="2">2 ★</option>
            <option value="3">3 ★</option>
            <option value="4">4 ★</option>
            <option value="5" selected>5 ★</option>
          </select>
          <button type="submit" name="submitComment" value="send" class="submitButton saveButton optimizedButton">send <i class="far fa-comment-alt"></i></button>
        </div>
      </form>
      <?PHP } ?>
      <div id="comments">
        <ul style="list-style-type: none;">
        <?php
          $comments = list_comments($tradeCommentSectionID);
          $userList = array();
          for($i=0;$i<count($comments);$i++) {
            $userList[$i] = $comments[$i]["user_id"];
          }
          $commentTextStr = "";
          for($i=0;$i<count($comments);$i++) {
            $commentText = $comments[$i]["reason"];
            echo '
            <li>
              <table class="commentStructureTable">
                <tr>
                  <td rowspan="3" style="height:100%"><a class="commentLine">&nbsp;</a></td>
                  <td><img class="userIMG" src="'.getImage($comments[$i]["user_id"]).'" alt="" /></td>
                  <td class="commentUsername">@'.strtolower(getName($comments[$i]["user_id"])).'</td>
                  <td class="commentRating">'.$comments[$i]["rating"].'</td>
                  <td><img class="goldStar" src="/assets/img/icons/gold_star.png" alt="" /></td>
                  <td style="width:100%"></td>
                </tr>
                <tr>
                  <td colspan="5" class="commentText">'.$commentText.'</td>
                </tr>';
            if (logedin() && get_comment($tradeCommentSectionID)["comment_id"] == $comments[$i]["comment_id"]) {
              echo '<tr><td colspan="5"><a class="commentDelete" href="https://swapitg.com/commentSection?trID='.$_GET["trID"].'&dl=1">delete</a></td></tr>';
            }
            echo '</table></li>';
          }
          function get_string_between($string, $start, $end){
              $string = ' ' . $string;
              $ini = strpos($string, $start);
              if ($ini == 0) return '';
              $ini += strlen($start);
              $len = strpos($string, $end, $ini) - $ini;
              return substr($string, $ini, $len);
          }
        ?>
        </ul>
      </div>
    </div>
  </body>
</html>
