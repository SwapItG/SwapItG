<?php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/utility.php");
	require_once(__DIR__ . "/session.php");
	require_once(__DIR__ . "/comment_section.php");

	//input: bool $orginal (if false the email will be passed into htmlspecialchars before return)
	function getEmail($orginal = false) {
		//logedin check
		if(logedin()) {
			global $pdo;

			//get email from database
			$sql = "SELECT email FROM user WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			if($orginal) {
				//return email
				return $sth->fetch()["email"];
			} else {
				//return escaped email
				return htmlspecialchars($sth->fetch()["email"]);
			}
		} else {
			return false;
		}
	}

	//input: int $user_id (if not set logedin user)
	function getName($user_id = -1) {
		//logedin or user_id check
		if(logedin() || $user_id != -1) {
			global $pdo;

			//get name from logedin/given user
			$sql = "SELECT name FROM user WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":id", ($user_id == -1) ? logedin() : $user_id, PDO::PARAM_INT);
			$sth->execute();
			if($sth->rowCount() == 0) {
				return false;
			}
			return $sth->fetch()["name"];
		} else {
			return false;
		}
	}

	//input: string $name
	//input: bool $check_only (if true the name is not set)
	function setName($name, $check_only = false) {
		//logedin check
		if(logedin()) {
			//check if name is valid
			if(!valid_name_check($name)) {
				return false;
			}

			if(!$check_only) {
				//set name
				global $pdo;
				$sql = "UPDATE user SET name = :name WHERE id = :id";
				$sth = $pdo->prepare($sql);
				$sth->bindValue(":name", htmlspecialchars($name), PDO::PARAM_STR);
				$sth->bindValue(":id", logedin(), PDO::PARAM_INT);
				$sth->execute();
			}
			return true;
		} else {
			return false;
		}
	}

	//input: int $user_id (if not set logedin user)
	function getSteamProfile($user_id = -1) {
		//logedin or user_id check
		if(logedin() || $user_id != -1) {
			global $pdo;

			//get steam id from logedin/given user
			$sql = "SELECT steam_id FROM user WHERE id = :id";
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

	//input: int $user_id (if not set logedin user)
	function getImage($user_id = -1) {
		//logedin or user_id check
		if(logedin() || $user_id != -1) {
			return "https://swapitg.com/profilePicture/" . (($user_id == -1) ? logedin() : $user_id);
		} else {
			return false;
		}
	}

	//important: <form enctype="multipart/form-data" method="post" ...
	//input: string $post_name (index of the post/files array where the image is stored)
	//return 0 -> worked
	//return 1 -> empty
	//return 2 -> file size to big
	//return 3 -> wrong file type
	//return 4 -> not loged in
	function setImage($post_name) {
		//logedin check
		if(logedin()) {
			//check if image is empty
			if(!isset($_FILES[$post_name]) || $_FILES[$post_name]["error"] != UPLOAD_ERR_OK) {
				return 1;
			}

			//check file size
			if($_FILES[$post_name]["size"] > (1 * 1024 * 1024)) { //1mb
				return 2;
			}

			//check imgae type
			if(exif_imagetype($_FILES[$post_name]["tmp_name"]) != IMAGETYPE_JPEG && exif_imagetype($_FILES[$post_name]["tmp_name"]) != IMAGETYPE_PNG) {
				return 3;
			}

			global $pdo;
			//set image
			$sql = "UPDATE user SET image = :image  WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":image", file_get_contents($_FILES[$post_name]["tmp_name"]), PDO::PARAM_STR);
			$sth->bindValue(":id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return 0;
		} else {
			return 4;
		}
	}

	//input: int $user_id (if not set logedin user)
	function getInfo($user_id = -1) {
		//logedin or user_id check
		if(logedin() || $user_id != -1) {
			global $pdo;
			//get info from logedin/given user
			$sql = "SELECT info FROM user WHERE id = :id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":id", ($user_id == -1) ? logedin() : $user_id, PDO::PARAM_INT);
			$sth->execute();
			if($sth->rowCount() == 0) {
				return false;
			}
			return $sth->fetch()["info"];
		} else {
			return false;
		}
	}

	//input: string $info (max. lenght 512)
	//input: bool $check_only (if true the name is not set)
	function setInfo($info, $check_only = false) {
		//logedin check
		if(logedin()) {
			//null or array check
			if(is_null($info) || is_array($info)) {
				return false;
			}
			//check info lenght
			if(strlen($info) > 512) {
				return false;
			}
			if(!$check_only) {
				global $pdo;
				//set info
				$sql = "UPDATE user SET info = :info  WHERE id = :id";
				$sth = $pdo->prepare($sql);
				$sth->bindValue(":info", htmlspecialchars($info), PDO::PARAM_STR);
				$sth->bindValue(":id", logedin(), PDO::PARAM_INT);
				$sth->execute();
			}
			return true;
		} else {
			return false;
		}
	}

	//input: int $user_id (if not set logedin user)
	function getTrades($user_id = -1) {
		//logedin or user_id check
		if(logedin() || $user_id != -1) {
			global $pdo;
			//get trades from logedin/given user
			$sql = "SELECT id FROM trade_proposal WHERE user_fk = :user_id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":user_id", ($user_id == -1) ? logedin() : $user_id, PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_COLUMN);
		} else {
			return false;
		}
	}

	//input: int $user_id (if not set logedin user)
	function getComments($user_id = -1) {
		//logedin or user_id check
		if(logedin() || $user_id != -1) {
			global $pdo;
			//get comments from logedin/given user
			$sql = "SELECT id AS comment_id, comment_section_fk AS comment_section_id FROM comment WHERE user_fk = :user_id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":user_id", ($user_id == -1) ? logedin() : $user_id, PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetchAll(PDO::FETCH_ASSOC);
		} else {
			return false;
		}
	}

	//input: int $user_id (if not set logedin user)
	function getCommentSection($user_id = -1) {
		//logedin or user_id check
		if(logedin() || $user_id != -1) {
			global $pdo;
			//get comment section from logedin/given user
			$sql = "SELECT comment_section_fk FROM user WHERE id = :user_id";
			$sth = $pdo->prepare($sql);
			$sth->bindValue(":user_id", ($user_id == -1) ? logedin() : $user_id, PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetch(PDO::FETCH_COLUMN);
		} else {
			return false;
		}
	}

	//input: int $user_id (if not set logedin user)
	function getUserCommentSectionStatus($user_id = -1) {
		//logedin or user_id check
		if(logedin() || $user_id != -1) {
			return get_status_comment_section(getCommentSection($user_id));
		} else {
			return false;
		}
	}

	//input: bool $status (if the comment section is enabled)
	function setUserCommentSectionStatus($status) {
		//logedin check
		if(!logedin()) {
			return false;
		}
		set_status_comment_section(getCommentSection(), $status);
		return true;
	}

	//input: string $name
	//input: string $info
	function setAll($name, $info) {
		//check if possible to set name and info
		if(setName($name, true) && setInfo($info, true)) {
			//set name and info
			setName($name);
			setInfo($info);
		} else {
			return false;
		}
	}

	function getAllUsers() {
			global $pdo;
			//
			$sql = "SELECT id,email,name,steam_id,image,info,verified FROM user WHERE 1";
			$sth = $pdo->prepare($sql);
			$sth->execute();
			if($sth->rowCount() == 0) {
				return false;
			}
			$users;
			for ($i=0;$i<$sth->rowCount();$i++) {
				$data = $sth->fetch();
				$users[$i]["id"] = $data["id"];
				$users[$i]["name"] = $data["name"];
				$users[$i]["image"] = getImage($users[$i]["id"]);
			}
			return $users;
	}
?>
