<?php
	require_once(__DIR__ . "/db_connect.php");
	require_once(__DIR__ . "/session.php");

	function create_comment_section() {
		global $pdo;
		$sql = "INSERT INTO comment_section () VALUES ()";
		$sth = $pdo->prepare($sql);
		$sth->execute();
		$sql = "SELECT LAST_INSERT_ID() AS id";
		$sth = $pdo->prepare($sql);
		$sth->execute();
		return $sth->fetch()["id"];
	}

	function delete_comment_section($comment_section_id) {
		global $pdo;
		$sql = "DELETE FROM comment WHERE comment_section_fk = :comment_section_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->execute();
		$sql = "DELETE FROM comment_section WHERE id = :comment_section_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->execute();
	}

	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty
	//return 3 -> rating has to be a value between 1-5
	//return 4 -> reason is to long
	//return 5 -> comment_section does not exist
	function create_comment($comment_section_id, $rating, $reason) {
		if(!logedin()) {
			return 1;
		}

		if(empty($comment_section_id) || is_array($comment_section_id) || empty($rating) || !is_numeric($rating) || is_null($reason) || is_array($reason)) {
			return 2;
		}

		$rating = intval($rating);

		if($rating < 1 || $rating > 5) {
			return 3;
		}

		if(strlen($reason) > 512) {
			return 4;
		}

		global $pdo;
		$sql = "SELECT id FROM comment_section WHERE id = :comment_section_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 5;
		}

		$sql = "SELECT id FROM comment WHERE comment_section_fk = :comment_section_id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			$sql = "INSERT INTO comment (comment_section_fk, rating, reason, user_fk) VALUES (:comment_section_id, :rating, :reason, :user_id)";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
			$sth->bindParam(":rating", $rating, PDO::PARAM_INT);
			$sth->bindParam(":reason", $reason, PDO::PARAM_STR);
			$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return 0;
		} else {
			$sql = "UPDATE comment SET rating = :rating, reason = :reason WHERE comment_section_fk = :comment_section_id AND user_fk = :user_id";
			$sth = $pdo->prepare($sql);
			$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
			$sth->bindParam(":rating", $rating, PDO::PARAM_INT);
			$sth->bindParam(":reason", $reason, PDO::PARAM_STR);
			$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
			$sth->execute();
			return 0;
		}
	}

	//return 0 -> worked
	//return 1 -> not logedin
	//return 2 -> some parameters are empty
	//return 3 -> comment not found
	function delete_comment($comment_section_id) {
		if(!logedin()) {
			return 1;
		}

		if(empty($comment_section_id) || is_array($comment_section_id)) {
			return 2;
		}

		global $pdo;
		$sql = "SELECT id FROM comment WHERE comment_section_fk = :comment_section_id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();

		if($sth->rowCount() == 0) {
			return 3;
		} else {
			$sql = "DELETE FROM comment WHERE id = :comment_id";
			$sth2 = $pdo->prepare($sql);
			$sth2->bindParam(":comment_id", $sth->fetch()["id"], PDO::PARAM_INT);
			$sth2->execute();
			return 0;
		}
	}

	//returns array("comment_id" => 1, "rating" => 5, "reason" => "reason test") or false
	function get_comment($comment_section_id) {
		if(!logedin() || !get_status_comment_section($comment_section_id)) {
			return false;
		}

		global $pdo;
		$sql = "SELECT id AS comment_id, rating, reason FROM comment WHERE comment_section_fk = :comment_section_id AND user_fk = :user_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->bindValue(":user_id", logedin(), PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		if($result == false) {
			return null;
		} else {
			return $result;
		}
	}

	//returns array("comment_section_id" => 1, "rating" => 5, "reason" => "reason test", "user_id" => 1)
	function get_comment_data($comment_id) {
		global $pdo;
		$sql = "SELECT comment_section_fk AS comment_section_id, rating, reason, user_fk AS user_id FROM comment WHERE id = :comment_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_id", $comment_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	function get_status_comment_section($comment_section_id) {
		global $pdo;
		$sql = "SELECT enabled FROM comment_section WHERE id = :comment_section_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->execute();
		return (bool) $sth->fetch()["enabled"];
	}

	function set_status_comment_section($comment_section_id, $status) {
		global $pdo;
		$sql = "UPDATE comment_section SET enabled = :status WHERE id = :comment_section_id";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->bindParam(":status", $status, PDO::PARAM_BOOL);
		$sth->execute();
	}

	function get_rating($comment_section_id) {
		if(!get_status_comment_section($comment_section_id)) {
			return 0;
		}

		global $pdo;
		$sql = "SELECT AVG(rating) AS rating FROM comment_section JOIN comment ON comment_section.id = comment.comment_section_fk WHERE comment_section.id = :comment_section_id GROUP BY comment_section_fk";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->execute();
		$result = $sth->fetch()["rating"];
		if(is_null($result)) {
			return 0;
		} else {
			return $result;
		}
	}

	function list_comments($comment_section_id) {
		if(!get_status_comment_section($comment_section_id)) {
			return false;
		}

		global $pdo;
		$sql = "SELECT id AS comment_id, rating, reason, user_fk AS user_id FROM comment WHERE comment_section_fk = :comment_section_id ORDER BY id DESC LIMIT 25";
		$sth = $pdo->prepare($sql);
		$sth->bindParam(":comment_section_id", $comment_section_id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
?>
