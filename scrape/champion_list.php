<?php

$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/core/init.php');
include_once($path . '/scrape/keys.php'); 

$championList = json_decode(file_get_contents("https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?champData=tags&api_key=" . $api_key))->data;

foreach ($championList as $champion) {
	$id = $champion->id;
	$name = $champion->name;
	$key = $champion->key;
	$title = $champion->title;
	$tags = serialize($champion->tags);

	/*
	// commented out to prevent calls to add more data; uncomment if using again!
	if ($insert->champion($id, $name, $key, $title, $tags, NULL)) {
		echo "Inserted champion " . $name . " into database";
	}
	*/
}

?>
