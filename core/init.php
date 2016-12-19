<?php
	session_start();
	require_once('connect/connect.php');
	require_once('classes/insert.php');
	require_once('classes/read.php');
	require_once('classes/update.php');

	$insert = new Insert($pdo);
	$read = new Read($pdo);
	$update = new Update($pdo);
?>