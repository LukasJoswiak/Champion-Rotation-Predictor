<?php

class Read {
	private $db;

	public function __construct($database) {
		$this->db = $database;
	}

	public function champion_list() {
		$stmt = $this->db->prepare("SELECT `champion_id`, `champion_name`, `champion_key`, `title`, `tags`, `date_released` FROM champions");

		try {
			$stmt->execute();
			
			return $stmt->fetchAll();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function champion($id) {
		$stmt = $this->db->prepare("SELECT `champion_name`, `champion_key`, `title`, `tags`, `date_released` FROM champions WHERE champion_id = :id");

		try {
			$stmt->execute(array(':id' => $id));
			
			return $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function rotation_week($week) {
		$stmt = $this->db->prepare("SELECT `champion_id` FROM rotation_history WHERE date_free = :week");

		try {
			$stmt->execute(array(
				':week' => $week
			));

			return $stmt->fetchAll();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function rotation_champion($champion_id) {
		$stmt = $this->db->prepare("SELECT `date_free` FROM rotation_history WHERE champion_id = :id");

		try {
			$stmt->execute(array(
				':id' => $champion_id
			));

			return $stmt->fetchAll();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}

?>