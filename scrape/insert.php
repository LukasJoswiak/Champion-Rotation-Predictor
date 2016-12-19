<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/core/init.php');
include_once($path . '/scrape/keys.php');

if (isset($_POST['data']) && isset($_POST['key'])) {
	$data = json_decode($_POST['data']);
	$key = $_POST['key'];

	if ($key === $insert_key) {
		$championList = json_decode(file_get_contents("https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?api_key=" . $api_key))->data;

		foreach ($data as $object) {
			$date = date("Y-m-d", strtotime($object->date));
			$champions = $object->champions;

			foreach ($champions as $champion) {
				foreach ($championList as $champ) {
					if ($champion == $champ->name) {
						$id = $champ->id;
					}
				}

				if (isset($id)) {
					echo $champion;
					/*
					// commented out to prevent calls to add more data; uncomment if using again!
					if ($insert->rotation_champion($id, $date)) {
						echo $champion . "(id=" . $id . ") inserted into database for rotation week: " . $date . "\n";
					}
					*/
				}
			}
		}
	}
}

?>
