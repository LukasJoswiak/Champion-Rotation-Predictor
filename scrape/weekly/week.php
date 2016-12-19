<?php

include_once('../keys.php');

if (isset($_POST['data'])) {
	$data = json_decode($_POST['data']);
	$key = $data->key;

	// make sure key matches in script
	if ($key === $week_key) {
		$path = $_SERVER['DOCUMENT_ROOT'];
		include_once($path . '/core/init.php');

		$freeChampions = json_decode(file_get_contents('https://na.api.pvp.net/api/lol/na/v1.2/champion?freeToPlay=true&api_key=' . $api_key))->champions;

		$tuesday = date('Y-m-d', strtotime('tuesday', strtotime('tomorrow')));

		foreach ($freeChampions as $champion) {
			$id = $champion->id;

			/*
			if ($insert->rotation_champion($id, $tuesday)) {
				echo "Inserted " . $id . " into database for date " . $tuesday . "\n";
			}
			 */
		}
	}
}

?>
