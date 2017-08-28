<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/core/init.php');
include_once($path . '/scrape/keys.php');

if (isset($_POST['data']) && isset($_POST['key'])) {
	$data = json_decode($_POST['data']);
	$key = $_POST['key'];

	if ($key === $update_key) {
		foreach ($data as $object) {
			$date = date("Y-m-d", strtotime($object->date));
			$champion = $object->champion;

			echo "FAKE, update file: Updated " . $champion . " with release date: " . $date . "\n";
			/*
			 * Uncomment when using
			if ($update->champion($champion, $date)) {
				echo "Updated " . $champion . " with release date: " . $date . "\n";
			}
			 */
		}
	}
}

?>
