<?php
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/trade.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/userdata_get_set.php");
  require_once($_SERVER['DOCUMENT_ROOT'] . "/php/comment_section.php");

  $tradeCommentSectionID = getTradeCommentSection($_GET["trID"]);

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
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <style>
      .userIMG {
        width:20px;
        height:20px;
        object-fit: cover;
        border-radius: 50%;
        padding-bottom:2px;
      }
      .goldStar {
        width:20px;
        margin-bottom:3px;
        margin-left:-5px;
      }
      .commentStructureTable {
        color:white;
      }
      .commentUsername {
        padding-left:10px;
        color:#AAA;
        font-family: arial;
        font-size: 13px;
        padding-bottom:2px;
      }
      .commentRating {
        padding-left:15px;
        font-family:sans-serif;
        font-size:12px;
      }
      .commentText {
        padding-left:5px;
        font-family: arial;
        font-size: 14px;
        padding-right:50px;
        word-break: break-all;
      }
      .commentLine {
        display:block;
        width:1px;
        background-color: orange;
        margin-right:15px;
        min-height:90px;
      }
      .commentDelete {
        position: absolute;
        font-size: 10px;
        color:var(--skyblue);
        margin-top:-6px;
        padding-left:4px;
        font-family: sans-serif;
      }
      .optimizedButton {
        height:30px;
        width:80px;
      }
      .optimizedButton:hover {
        border:none;
        height:30px;
      }
      #commentSectionTitle {
        font-family: sans-serif;
        color:white;
        padding-left:5px;
        letter-spacing: 0.65px;
      }
      .commentInput {
        border-radius:2px;
        border:none;
        padding-left:5px;
        height:30px;
        transition: 0.25s;
        width:300px;
      }
      .commentInput:hover {
        background-color:#DDD;
      }
      .commentInput:focus {
        margin-bottom:10px;
        transition: 0.5s;
      }
      .commentRatingSelect {
        height:32px;
        width:50px;
        font-family: arial;
        border-radius: 4px;
        background-color: #222;
        color:#f9f079;
        outline: none;
        border:none;
        padding-left: 5px;
      }
      .commentRatingSelect:hover {
        cursor:pointer;
        background-color: #000;
      }
      @media only screen and (max-width: 550px) {
        .commentInput:focus {
          width:100%;
          margin-bottom:10px;
          transition: 0.5s;
        }
      }
    </style>
  </head>
  <body>
    <h3 id="commentSectionTitle">RATE THE TRADE:</h3>
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
