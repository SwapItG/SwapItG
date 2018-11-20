<?PHP
function output_trade_header_html($trade,$tradeID) {
  echo '
  <table class="contentUserPostTable" style="width:100%">
    <tr>
      <td colspan="3">
        <table class="contentUserPostHeaderTrade">
          <tr>
            <td class="contentUserPostNameDIV">
              <img class="userIMG lazyload" data-src="'.getImage($trade["user_id"]).'"/>
              <a class="userName" href="https://swapitg.com/userTrade?trID='.$tradeID.'">'.getName($trade["user_id"]).'</a>
            </td>
            <td class="contentUserPostHeaderTradeGame">'.getGameName($trade["game_id"]).'
              <img class="gameIcon lazyload" data-src="'.getGameIcon($trade["game_id"]).'" />
            </td>
            <td class="contentUserPostTime">'.calculate_time_span($trade["creation_time"],getName($trade["user_id"])).'</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <div class="item_flex_box">';
}
function output_trade_offer_html($item_row_count,$trade,$tradeID) {
  echo "<div class='item_div_flex'><table class='item_table item_table_has'><tr><th class='userPostTH' colspan='".$item_row_count."'>HAS</th></tr><tr>";
  $row_split = 0;
  for ($j=0;$j<count($trade["item_offer"]);$j++) {
    $tradeData = $trade["item_offer"][$j];
    $row_split++;
    echo '<td class="item_table_cell" onmouseout="hideAttributes(this,`attribute_'.$trade["user_id"].'_'.$j.'_'.$tradeID."_HAS".'`)" onmouseover="displayAttributes(this,`attribute_'.$trade["user_id"].'_'.$j.'_'.$tradeID."_HAS".'`)">'.create_item_display($tradeData,$j,$trade,$tradeID,"HAS").'</td>';
    if ($row_split >= $item_row_count) {
      echo "</tr><tr>";
      $row_split = 0;
    }
  }
  //make_trade_full_row($row_split,$item_row_count);
  echo "</tr></table></div>";
}
function output_trade_demand_html($item_row_count,$trade,$tradeID) {
  echo "<div class='item_div_flex'><table class='item_table item_table_wants'><tr><th colspan='".$item_row_count."'>WANT</th></tr><tr>";
  $row_split = 0;
  for ($j=0;$j<count($trade["item_demand"]);$j++) {
    $tradeData = $trade["item_demand"][$j];
    $row_split++;
    echo '<td class="item_table_cell" onmouseout="hideAttributes(this,`attribute_'.$trade["user_id"].'_'.$j.'_'.$tradeID."_WANT".'`)" onmouseover="displayAttributes(this,`attribute_'.$trade["user_id"].'_'.$j.'_'.$tradeID."_WANT".'`)">'.create_item_display($tradeData,$j,$trade,$tradeID,"WANT").'</td>';
    if ($row_split >= $item_row_count) {
      echo "</tr><tr>";
      $row_split = 0;
    }
  }
  //make_trade_full_row($row_split,$item_row_count);
  echo "</tr></table></div>";
}
function output_trade_footer_htlm($trade) {
      echo '</div>
      <div style="font-size:13px"><textarea class="userPostDescriptionArea" disabled="">'.$trade["description"].'</textarea></div>
      </div></td></tr>';
}
function output_trade_html($trade,$item_row_count,$tradeID) {
  output_trade_header_html($trade,$tradeID);
  // Output for User Offer
  output_trade_offer_html($item_row_count,$trade,$tradeID);
  // Output for User Demand
  output_trade_demand_html($item_row_count,$trade,$tradeID);
  // Output for trade footer
  output_trade_footer_htlm($trade);
}
//completes a trade to full row (optional)
function make_trade_full_row($current,$max) {
  $current = $max - $current;
  if ($current == $max) {
    return 0;
  }
  for($k=0;$k<$current;$current--) {
    echo "<td></td>";
  }
}
function create_item_display($tradeData,$j,$trade,$tradeID,$desire) {
  if ($tradeData["count"]>1) {
    $display .= "<table class='item_contain'><tr><td class='item_count_td'><div  class='item_count'>".$tradeData["count"]."x</div><td style='display:block;width:125px'></td></tr><tr></td><td colspan='2' class='item_name'>".$tradeData["name"]."</td></tr></table>";
  } else {
    $display .= "<table class='item_contain'><tr></td><td colspan='2' class='item_name'>".$tradeData["name"]."</td></tr></table>";
  }
  $display .= add_attribute_window($tradeData,$j,$trade,$trade["user_id"],$tradeID,$desire);
  return $display;
}
function calculate_time_span($date,$playername) {
  $time_ago;
  $current_timestamp = time();
  $timestamp_seconds = 60;
  $timestamp_minutes;
  $timestamp_hours = 3600;
  $timestamp_days = 86400;
  $time_end_string;
  $time_ago = ($current_timestamp - strtotime($date));
  if ($time_ago < $timestamp_seconds) {
    $time_ago = round($time_ago);
    $time_end_string = " sec.";
  } else if ($time_ago > $timestamp_seconds && $time_ago < $timestamp_hours) {
    $time_ago = round($time_ago/60);
    $time_end_string = " min.";
  } else if ($time_ago > $timestamp_hours && $time_ago < $timestamp_days) {
    $time_ago = round($time_ago/60/60);
    $time_end_string = " hours";
  } else if ($time_ago > $timestamp_days) {
    $time_ago = round($time_ago/60/60/24);
    $time_end_string = " days";
  }
  return "<span style='color:grey'>".$playername." </span> ".$time_ago.$time_end_string." ago";
}
function add_attribute_window($tradeData,$round,$trade,$uID,$tradeID,$desire) {
  $attr .= '<div class="attribute_table" id="attribute_'.$uID.'_'.$round.'_'.$tradeID."_".$desire.'">';
  $attr .= "<table>";
  for ($i=0;$i<count($tradeData["attributes"]);$i++) {
    $attr .= "<tr><td>".$tradeData["attributes"][$i]["name"]."</td></tr>";
  }
  $attr .= "</table></div>";
  return $attr;
}
?>
