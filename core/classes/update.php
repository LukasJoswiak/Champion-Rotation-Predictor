<?php

class Update {
	private $db;

	public function __construct($database) {
		$this->db = $database;
	}

	public function champion($name, $date) {
		$stmt = $this->db->prepare("UPDATE champions SET `date_released` = :date WHERE `champion_name` = :champion_name");

		try {
			$stmt->execute(array(
				':date' => $date,
				':champion_name' => $name
			));
			
			return true;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}

?>