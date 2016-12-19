<?php

include_once('../keys.php');

if (isset($_POST['data'])) {
    $data = json_decode($_POST['data']);
    $key = $data->key;

    // make sure key matches in script
    if ($key === $week_key) {
        $path = $_SERVER['DOCUMENT_ROOT'];
        include_once($path . '/core/init.php');

        $champions = $data->champions;

        $championList = json_decode(file_get_contents("https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?api_key=" . $api_key))->data;

        $tuesday = date('Y-m-d', strtotime('tuesday', strtotime('tomorrow')));

        foreach ($champions as $champion) {
            foreach ($championList as $champ) {
                if ($champion == $champ->name) {
                    $id = $champ->id;
                    break;
                }
            }

            if ($insert->rotation_champion($id, $tuesday)) {
                echo "Inserted " . $id . " into database for date " . $tuesday . "\n";
            }
        }
    }
}

?>
