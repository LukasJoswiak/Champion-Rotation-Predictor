<?php

class Insert {
	private $db;

	public function __construct($database) {
		$this->db = $database;
	}

	public function champion($id, $name, $key, $title, $tags, $released) {
		$stmt = $this->db->prepare("INSERT INTO champions (`champion_id`, `champion_name`, `champion_key`, `title`, `tags`, `date_released`) VALUES (:id, :name, :key, :title, :tags, :released)");

		try {
			$stmt->execute(array(
				':id' => $id,
				':name' => $name,
				':key' => $key,
				':title' => $title,
				':tags' => $tags,
				':released' => $released
			));

			return true;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function rotation_champion($id, $date) {
		$stmt = $this->db->prepare("INSERT INTO rotation_history (`champion_id`, `date_free`) VALUES (:id, :date)");

		try {
			$stmt->execute(array(
				':id' => $id,
				':date' => $date
			));

			return true;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}

?>