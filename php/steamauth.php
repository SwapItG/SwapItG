<?php
	//Parts of the code are from there: https://github.com/SmItH197/SteamAuthentication under the MIT license http://opensource.org/licenses/mit-license.php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/session.php");
	require_once(__DIR__ . "/steam_config.php");

	function steam_loginbutton($buttonstyle = "square") {
		$button['rectangle'] = "01";
		$button['square'] = "02";
		$button = "<a href='?steam_login' id='steam_login_link'><img src='https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_".$button[$buttonstyle].".png' id='steam_login_image'></a>";

		echo($button);
	}

	function steam_logedin($user_id = -1) {
		if(logedin() || $user_id != -1) {
			global $pdo;
			$sql = "SELECT steam_id FROM user WHERE id = :id AND steam_id IS NOT NULL";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":id", ($user_id == -1) ? logedin() : $user_id, PDO::PARAM_INT);
			$sth->execute();
			if($sth->rowCount() == 0) {
				return false;
			}
			return $sth->fetch()["steam_id"];
		} else {
			return false;
		}
	}

	//returns array("name" => "steam user name", "profile_url" => "steam profile url")
	function get_steam_data($user_id = -1) {
		if(logedin() || $user_id != -1) {
			$steam_id = steam_logedin($user_id);
			if(!empty($steam_id)) {
				global $steamauth;
				$url = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$steamauth['apikey']."&steamids=".$steam_id);
				$content = json_decode($url, true);
				$result = array();
				$result["name"] = $content['response']['players'][0]['personaname'];
				$result["profile_url"] = $content['response']['players'][0]['profileurl'];
				return $result;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function steam_logout() {
		if(steam_logedin()) {
			global $pdo;
			$sql = "UPDATE user SET steam_id = NULL WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return true;
		} else {
			return false;
		}
	}

	if (isset($_GET['steam_login'])){
		require 'openid.php';
		try {
			$openid = new LightOpenID($steamauth['domainname']);

			if(!$openid->mode) {
				$openid->identity = 'https://steamcommunity.com/openid';
				header('Location: ' . $openid->authUrl());
			} elseif ($openid->mode == 'cancel') {
				echo 'User has canceled authentication!';
			} else {
				if($openid->validate()) {
					$id = $openid->identity;
					$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
					preg_match($ptn, $id, $matches);

					if(logedin()) {
						global $pdo;
						$sql = "UPDATE user SET steam_id = :steam_id WHERE id = :id";
						$sth = $pdo->prepare($sql);
						$sth->bindParam(":steam_id", $matches[1], PDO::PARAM_STR);
						$sth->bindValue(":id", logedin(), PDO::PARAM_INT);
						$sth->execute();
					}
					if (!headers_sent()) {
						header('Location: '.$steamauth['loginpage']);
						exit;
					} else {
						?>
						<script type="text/javascript">
							window.location.href="<?=$steamauth['loginpage']?>";
						</script>
						<noscript>
							<meta http-equiv="refresh" content="0;url=<?=$steamauth['loginpage']?>" />
						</noscript>
						<?php
						exit;
					}
				} else {
					echo "User is not logged in.\n";
				}
			}
		} catch(ErrorException $e) {
			echo $e->getMessage();
		}
	}
?>
