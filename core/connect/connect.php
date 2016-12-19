<?php
	try {
		$host = "localhost";
		$dbname = "league";
		$port = 3306;
		$user = "root";
		$pass = "root";
		$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
?>
