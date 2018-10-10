<?PHP

function checkSteamName($namestring) {
    $firstletter = substr($namestring, 0, 1);
    $firstletter = mb_strtolower($firstletter);
    if (is_numeric($firstletter) || ctype_alpha($firstletter)) {
        return true;
    } else {
        return false;
    }
}
function getSteamID($customUrlName) {
    if (checkSteamName($customUrlName) == false) {
        return false;
    };
    $api_key = "0D58D44BC7880556856D0AC345325F4A";
    $api_url = 'http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key=' . $api_key . '&vanityurl=' . $customUrlName;
    $json = json_decode(file_get_contents($api_url), true);
    if (empty($json["response"]["steamid"])) {
        return null;
    }
    return $json["response"]["steamid"];
}
function getSteamInfo($steamid) {
    $api_key = "0D58D44BC7880556856D0AC345325F4A";
    $api_url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$api_key&steamids=$steamid";
    $json = json_decode(file_get_contents($api_url), true);
    return $json;
}
function getSteamName($steamid) {
    $data = getSteamInfo($steamid);
    return $data["response"]["players"][0]["personaname"];
}
function getSteamURL($steamid) {
    $data = getSteamInfo($steamid);
    return $data["response"]["players"][0]["profileurl"];
}
function getSmallImage($steamid) {
    $data = getSteamInfo($steamid);
    return $data["response"]["players"][0]["avatar"];
}
function getImage($steamid) {
    $data = getSteamInfo($steamid);
    return $data["response"]["players"][0]["avatarmedium"];
}
function getLargeImage($steamid) {
    $data = getSteamInfo($steamid);
    return $data["response"]["players"][0]["avatarfull"];
}
function getRealName($steamid) {
    $data = getSteamInfo($steamid);
    return $data["response"]["players"][0]["realname"];
}
?>
